<?php
    return array(   
      'news/([0-9]+)' => 'news/view/$1',
        
      'news' => 'news/index', //actionIndex in NewsController
      'product' => 'product/list' //actionList in ProductController
    );