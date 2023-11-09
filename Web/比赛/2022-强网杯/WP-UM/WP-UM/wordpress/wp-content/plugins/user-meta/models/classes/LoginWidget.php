<?php
namespace UserMeta;

/**
 * Building LoginWidget
 *
 * @author Khaled Hossain
 * @since 1.2.1
 */
class LoginWidget extends \WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        global $userMeta;

        parent::__construct('umLogin', // Base ID
        __('User Meta Login', $userMeta->name), // Name
        array(
            'description' => __('Login Widget', $userMeta->name)
        )); // Args
    }

    /**
     * Front-end display of widget.
     *
     * @see \WP_Widget::widget()
     * @param array $args
     *            Widget arguments.
     * @param array $instance
     *            Saved values from database.
     */
    public function widget($args, $instance)
    {
        global $userMeta, $post;

        extract($args);

        if (! empty($instance['hide_from_login_page'])) {
            $login = $userMeta->getSettings('login');
            if (@$login['login_page'] == $post->ID)
                return;
        }

        $title = is_user_logged_in() ? @$instance['user_title'] : @$instance['guest_title'];
        $title = apply_filters('widget_title', $title);

        echo $before_widget;
        if (! empty($title))
            echo $before_title . $title . $after_title;

        echo $userMeta->userLoginProcess();

        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see \WP_Widget::update()
     * @param array $new_instance
     *            Values just sent to be saved.
     * @param array $old_instance
     *            Previously saved values from database.
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['guest_title'] = strip_tags($new_instance['guest_title']);
        $instance['user_title'] = strip_tags($new_instance['user_title']);
        $instance['hide_from_login_page'] = strip_tags($new_instance['hide_from_login_page']);

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see \WP_Widget::form()
     * @param array $instance
     *            Previously saved values from database.
     */
    public function form($instance)
    {
        global $userMeta;

        $guest_title = isset($instance['guest_title']) ? $instance['guest_title'] : __('Login', $userMeta->name);
        $user_title = isset($instance['user_title']) ? $instance['user_title'] : null;
        $hide_from_login_page = isset($instance['hide_from_login_page']) ? $instance['hide_from_login_page'] : false;

        echo $userMeta->createInput($this->get_field_name('guest_title'), 'text', array(
            'value' => esc_attr($guest_title),
            'label' => __('Guest Title:', $userMeta->name),
            'id' => $this->get_field_id('guest_title'),
            'class' => 'widefat',
            'after' => '<i>' . __('Only guest users can see this title', $userMeta->name) . '</i>',
            'enclose' => 'p'
        ));

        echo $userMeta->createInput($this->get_field_name('user_title'), 'text', array(
            'value' => esc_attr($user_title),
            'label' => __('Logged-In User Title:', $userMeta->name),
            'id' => $this->get_field_id('user_title'),
            'class' => 'widefat',
            'after' => '<i>' . __('Only logged-in users can see this title', $userMeta->name) . '</i>',
            'enclose' => 'p'
        ));

        echo $userMeta->createInput($this->get_field_name('hide_from_login_page'), 'checkbox', array(
            'value' => esc_attr($hide_from_login_page),
            'label' => __('Hide this widget when viewing login page', $userMeta->name),
            'id' => $this->get_field_id('hide_from_login_page'),
            'class' => 'checkbox',
            'enclose' => 'p'
        ));
    }
}