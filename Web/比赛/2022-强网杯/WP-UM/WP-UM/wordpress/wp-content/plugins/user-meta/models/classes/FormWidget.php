<?php
namespace UserMeta;

/**
 * Building FormWidget
 *
 * @author Khaled Hossain
 * @since 1.2.1
 */
class FormWidget extends \WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        global $userMeta;

        parent::__construct('umForm', // Base ID
        __('User Meta Form', $userMeta->name), // Name
        array(
            'description' => __('Show user registration, profile or login form as widget', $userMeta->name)
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
        global $userMeta;
        extract($args);

        $title = is_user_logged_in() ? @$instance['user_title'] : @$instance['guest_title'];
        $title = apply_filters('widget_title', $title);

        echo $before_widget;
        if (! empty($title))
            echo $before_title . $title . $after_title;

        // echo $userMeta->userUpdateRegisterProcess( @$instance['action_type'], @$instance['form_name'] );

        $actionType = strtolower(@$instance['action_type']);
        $form = @$instance['form_name'];
        $diff = @$instance['diff'];

        // Replace "both" to "profile-registration" and "none" to "public"
        $actionType = str_replace(array(
            'both',
            'none'
        ), array(
            'profile-registration',
            'public'
        ), $actionType);

        if (in_array($actionType, array(
            'registration',
            'profile',
            'profile-registration',
            'public'
        )))
            echo $userMeta->userUpdateRegisterProcess($actionType, $form, $diff);
        elseif ($actionType == 'login')
            echo $userMeta->userLoginProcess($form);
        else
            echo $userMeta->showError(sprintf(__('type="%s" is invalid.', $userMeta->name), $actionType), false);

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
        return array_map('strip_tags', $new_instance);

        /*
         * $instance = array();
         * $instance[ 'guest_title' ] = strip_tags( $new_instance[ 'guest_title' ] );
         * $instance[ 'user_title' ] = strip_tags( $new_instance[ 'user_title' ] );
         * $instance[ 'hide_from_login_page' ] = strip_tags( $new_instance[ 'hide_from_login_page' ] );
         *
         * return $instance;
         */
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

        $guest_title = isset($instance['guest_title']) ? $instance['guest_title'] : null;
        $user_title = isset($instance['user_title']) ? $instance['user_title'] : null;
        $action_type = isset($instance['action_type']) ? $instance['action_type'] : null;
        $form_name = isset($instance['form_name']) ? $instance['form_name'] : null;
        $diff = isset($instance['diff']) ? $instance['diff'] : null;

        $formsList = $userMeta->getFormsName();
        array_unshift($formsList, null);

        $actionTypes = $userMeta->validActionType();
        array_unshift($actionTypes, null);

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

        echo $userMeta->createInput($this->get_field_name('action_type'), 'select', array(
            'value' => esc_attr($action_type),
            'label' => __('Action Type:', $userMeta->name),
            'id' => $this->get_field_id('action_type'),
            'class' => 'widefat',
            'enclose' => 'p'
        ), $actionTypes);

        echo $userMeta->createInput($this->get_field_name('form_name'), 'select', array(
            'value' => esc_attr($form_name),
            'label' => __('Form Name:', $userMeta->name),
            'id' => $this->get_field_id('form_name'),
            'class' => 'widefat',
            'enclose' => 'p'
        ), $formsList);

        echo $userMeta->createInput($this->get_field_name('diff'), 'text', array(
            'value' => esc_attr($diff),
            'label' => __('Diff (optional):', $userMeta->name),
            'id' => $this->get_field_id('diff'),
            'class' => 'widefat',
            'after' => '<i>' . __('For showing role based on user profile', $userMeta->name) . '</i>',
            'enclose' => 'p'
        ));
    }
}