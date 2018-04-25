Victoire CMS Form Bundle
============

##What is the purpose of this bundle

This bundle gives you access to the *Form Widget*.
With this widget, you can install any contact form.

##Set Up Victoire

If you haven't already, you can follow the steps to set up Victoire *[here](https://github.com/Victoire/victoire/blob/master/setup.md)*

##Install the Bundle :

Run the following composer command :

    php composer.phar require friendsofvictoire/form-widget

Do not forget to add the bundle in your AppKernel !

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Victoire\Widget\FormBundle\VictoireWidgetFormBundle(),
            );

            return $bundles;
        }
    }

##Inject Data before send mail

When widget is configure to send mail with form data, you can inject some other data before send mail.

###Create a EventLister :
    <?php
    namespace AppBundle\EventListener;
    
    use Symfony\Component\EventDispatcher\Event;
    
    class WidgetFormListener
    {
        public function injectData(Event $event)
        {
            $event->prependData('new label', 'before post data');
            $event->appendData('another label', 'after post data');
        }
    }

###Declare listener in Service :
    #service.yml
    
    widget_form_listener:
        class: AppBundle\EventListener\WidgetFormListener
        tags:
            - { name: kernel.event_listener, event: victoire.widget_form.pre_send_mail, method: injectData }