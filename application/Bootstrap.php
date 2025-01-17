<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initHelpers(){
        Yaf_Loader::import( BASE_PATH .'/application/helper/function.php' );
        Yaf_Loader::import( BASE_PATH .'/application/helper/file.php' );
                
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            if(Yaf_Registry::get('ping_error')){
                Yaf_Registry::del('ping_error');
                return true;
            }
            if(stripos($errstr, 'MySQL server has gone away')!==false){
                return true;
            }
            
            log_message('error', 'error('. $errno .'): '.$errstr.' file:'.$errfile.' line '.$errline);
            lExit(json_encode(array('rtn'=>$errno+100000, 'error_msg'=>$errstr)));
        });
        
        $tmp = function($e){
            $tmp = $e->getPrevious();
            !empty($tmp) && $e = $tmp;
            log_message('error', $e->getMessage() .'('. $e->getCode() .') at file: '. $e->getFile() .' in line: '. $e->getLine() ."\n");
            lExit(json_encode(array('rtn'=>$e->getCode()+10000, 'error_msg'=>$e->getMessage())));
        };
        set_exception_handler($tmp);

        spl_autoload_register(function($name) {
            $args = explode('_', $name);

            if(count($args)===2){
                $_database = strtolower($args[0]);
                $_className = str_replace('Model', '', $args[1]);
                $_table = preg_replace('/^_|_$/', '', strtolower(hump2Line($_className)));
                $_dir = ucfirst($_database);

                $tpl = <<<EOF
<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class {$_dir}_{$_className}Model extends BaseModel {
    public static \$_table = '{$_table}';
    public static \$_database = '{$_database}';
}
EOF;
                $path = realpath(BASE_PATH).DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$_dir;
                if(!file_exists($path)){
                    mkdir($path, '0744', true);
                }

                $file = $path.DIRECTORY_SEPARATOR.$_className.'.php';
                if(file_exists($file)){
                    log_message('error', $file.'已存在,不再初始化model文件');
                    return true;
                }

                file_put_contents($file, $tpl);

                require_once $file;
                return true;
            }else if(count($args)===1){
                $_database = 'agent';
                $_className = str_replace('Model', '', $args[0]);
                $_table = preg_replace('/^_|_$/', '', strtolower(hump2Line($_className)));

                $tpl = <<<EOF
<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class {$_className}Model extends BaseModel {
    public static \$_table = '{$_table}';
    public static \$_database = '{$_database}';
}
EOF;
                $path = realpath(BASE_PATH).DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'models';
                if(!file_exists($path)){
                    mkdir($path, '0744', true);
                }

                $file = $path.DIRECTORY_SEPARATOR.$_className.'.php';
                if(file_exists($file)){
                    log_message('error', $file.'已存在,不再初始化model文件');
                    return true;
                }

                file_put_contents($file, $tpl);

                require_once $file;
                return true;
            }

            throw new Exception('class '. $name . ' not found!', 500);
        });
    }

    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $arrConfig);
        Yaf_Loader::import( BASE_PATH .'/conf/constants.php' );
        ini_set('session.name', $arrConfig['application']['session']['name']);
        ini_set('session.save_handler', $arrConfig['application']['session']['save_handler']);
        ini_set('session.save_path', $arrConfig['application']['session']['save_path']);
        ini_set('session.gc_maxlifetime', $arrConfig['application']['session']['gc_maxlifetime']);
        ini_set('session.cookie_lifetime', $arrConfig['application']['session']['gc_maxlifetime']);
        Yaf_Session::getInstance()->start();
    }
    
    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        !is_cli() && $dispatcher->registerPlugin(new LogPlugin());
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由
        $request = new Yaf_Request_Http();
        if(!is_cli()){
            Yaf_Registry::set('defaultModule', 'Index');
            
            $dispatcher->getRouter()->addRoute('route', new Router());
        }
    }
    
    public function _initView(Yaf_Dispatcher $dispatcher){
            //在这里注册自己的view控制器，例如smarty,firekylin
    }
}
