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
