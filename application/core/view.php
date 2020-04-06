<?php
class View
{
    public $template_view;
    function generate($content_view, $template_view, $data = null)
    {
        if(is_array($data))
        {
            if (isset($_SESSION['login'])){
                include 'application/core/notifications.php';
                $notification = new Notifications();
                $data['notification'] = $notification->get_notification($_SESSION['login']);
            }
            extract($data);

        }
        include 'application/views/'.$template_view;
    }

}
