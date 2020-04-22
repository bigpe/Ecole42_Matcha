<?php

class Controller_Politics extends Controller
{
    function action_index()
    {
        $this->view->generate('politics_view.php', 'template_view.php');
    }
}
