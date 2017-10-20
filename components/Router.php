<?php
    class Router
    {
        private $routes;

        public function __construct()
        {
            $routesPath = ROOT.'/config/routes.php';
            $this-> routes = include($routesPath);
        }

        /*
         * Returns request string
         * @return string
         */
        
        private function getURI()
        {
            if(!empty($_SERVER['REQUEST_URI'])) {
                return trim($_SERVER['REQUEST_URI'], '/');
            }
        }
        
        public function run()
        {
            //Получить строку запроса
            $uri = $this->getURI();
            
            //Проверить наличие такого запроса в routes.php
            foreach ($this->routes as $uriPattern => $path) {
                //Cравниваем $uriPattern и $uri
                if(preg_match("~$uriPattern~", $uri)) {
                    //Определяем какой контроллер и действие обрабатывают запрос
//                    echo "<br>Где ищем(запрос, который набрал пользователь): $uri";
//                    echo "<br>Что ищем(совпадение из правила): $uriPattern";
//                    echo "<br>Кто обрабатывает: $path";
                    
                    $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                    
//                    echo "<br><br>Нужно сформировать: $internalRoute";
                    
                    $segments = explode('/', $internalRoute);
                    
                    $controllerName = array_shift($segments).'Controller';
                    $controllerName = ucfirst($controllerName);
                    
                    $actionName = 'action'.ucfirst(array_shift($segments));
                    
                    $parameters = $segments;
                    //Подключить файл класса-контроллера

                    $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                    if(file_exists($controllerFile)) {
                        include_once($controllerFile);
                    }

                    //Создать объект, вызвать метод(действие)
                    $controllerObject = new $controllerName;
                    
                    $result = call_user_func_array(array($controllerObject, $actionName), 
                            $parameters);
                    
                    if($result != null) {
                        break;
                    }
                }
            }        
        }
    }
