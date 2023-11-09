<?php
namespace UserMeta;

/**
 * Building editor for 'Forms' and 'Shared Fields' menu.
 *
 * @author Khaled Hossain
 * @since 1.1.7
 */
class FormBuilder extends FormBase
{

    /**
     *
     * @var (array) Form builder elements.
     */
    protected $elements;

    /**
     *
     * @var (string) Editor Name: fields_editor, form_editor, form_generate
     */
    protected $editor;

    protected $nonce;

    private $maxID;

    public $redirect_to;

    /**
     *
     * @param (string) $editor:
     *            fields_editor, form_editor, form_generate
     * @param (string) $formName
     */
    function __construct($editor = null, $formName = null)
    {
        parent::__construct($formName);

        $this->editor = $editor;

        if (! empty($this->editor))
            $this->_initEditor();
    }

    private function _initEditor()
    {
        $this->nonce = wp_create_nonce('pf' . ucwords($this->editor));

        switch ($this->editor) {

            case 'fields_editor':
                break;

            case 'form_editor':
                $this->_elementList();
                $this->_setFormFieldsDropdown();
                break;
        }
    }

    private function _setFormFieldsDropdown()
    {
        global $userMeta;

        $fieldsType = $userMeta->umFields();

        $fieldsList = array();
        if (! empty($this->data['fields']) && is_array($this->data['fields'])) {
            foreach ($this->data['fields'] as $id => $field) {
                if (empty($field['field_type']))
                    continue;

                if (! empty($fieldsType[$field['field_type']]['field_group'])) {
                    if ('formatting' == $fieldsType[$field['field_type']]['field_group'])
                        continue;
                }

                $typeTitle = $fieldsType[$field['field_type']]['title'];
                $label = 'ID:' . $id . ' (' . $typeTitle . ') ';
                if (! empty($field['field_title']))
                    $label .= $field['field_title'];
                $fieldsList[$id] = esc_html($label);
            }
        }

        FieldBuilder::$formFieldsDropdown = $fieldsList;
    }

    /**
     * Set form builder elements to $this->elements.
     */
    private function _elementList()
    {
        global $userMeta;

        $elements = array(
            'button_title' => array(
                'label' => __('Submit Button Title', $userMeta->name),
                'placeholder' => __('Keep blank for default value', $userMeta->name)
            ),
            'button_class' => array(
                'label' => __('Submit Button Class', $userMeta->name),
                'placeholder' => __('Assign class to submit button', $userMeta->name)
            ),
            'form_class' => array(
                'label' => __('Form Class', $userMeta->name),
                'placeholder' => __('Keep blank for default value', $userMeta->name)
            ),
            'disable_ajax' => array(
                'type' => 'checkbox',
                'label' => __('Do not use AJAX submit', $userMeta->name)
            )
        );

        $this->elements = $elements;
    }

    function displaySettings()
    {
        global $userMeta;

        extract($this->data);

        $html = null;
        foreach ($this->elements as $name => $args) {
            $args = wp_parse_args($args, array(
                'type' => 'text',
                'value' => isset($$name) ? $$name : null,
                'class' => 'form-control',
                'label_class' => 'col-sm-2 control-label',
                'field_enclose' => 'div class="col-sm-6"',
                'enclose' => 'div class="form-group"'
            ));

            $type = $args['type'];
            unset($args['type']);

            if ('checkbox' == $type) {
                $args['label_class'] = 'col-sm-offset-2 col-sm-6';
                $args['field_enclose'] = '';
                $args['enclose'] = 'p class="form-group"';
            }

            $options = array();
            if (isset($args['options'])) {
                $options = $args['options'];
                unset($args['options']);
            }

            $html .= $userMeta->createInput($name, $type, $args, $options);
        }

        $html = '<div class="form-horizontal" role="form">' . $html . '</div>';

        return $html;
    }

    function displayFormFields()
    {
        global $userMeta;

        if (! empty($this->data['fields']) && is_array($this->data['fields'])) {
            foreach ($this->data['fields'] as $field) {
                $fieldBuilder = new FieldBuilder($field);
                $fieldBuilder->setEditor($this->editor);
                echo $fieldBuilder->buildPanel();
            }
        }
    }

    function displayAllFields()
    {
        global $userMeta;

        foreach ($this->sharedFields as $id => $field) {
            $field['id'] = $id;
            $fieldBuilder = new FieldBuilder($field);
            $fieldBuilder->setEditor($this->editor);
            echo $fieldBuilder->buildPanel();
        }
    }

    function getMaxFieldID()
    {
        global $userMeta;

        $config = $userMeta->getData('config');

        if (! empty($config['max_field_id']))
            return (int) $config['max_field_id'];

        $maxs = array();

        if (! empty($this->sharedFields))
            $maxs[] = max(array_keys($this->sharedFields));

        $forms = $this->getRawForms();
        if (! empty($forms) && is_array($forms)) {
            foreach ($forms as $form) {
                if (! empty($form['fields']) && is_array($form['fields']))
                    $maxs[] = max(array_keys($form['fields']));
            }
        }

        return ! empty($maxs) ? max($maxs) : 0;
    }

    function setMaxFieldID($id = 0)
    {
        global $userMeta;

        if (empty($id))
            $id = $this->maxID;

        if (empty($id))
            return;

        $config = $userMeta->getData('config');
        $config = is_array($config) ? $config : array();
        $config['max_field_id'] = (int) $id;
        $userMeta->updateData('config', $config);
    }

    function maxFieldInput()
    {
        $id = $this->getMaxFieldID();
        return '<input type="hidden" name="init_max_id" id="um_init_max_id" value="' . $id . '"/>' . '<input type="hidden" name="max_id" id="um_max_id" value="' . $id . '"/>';
    }

    function additional()
    {
        $html = '';
        $html .= '<input type="hidden" id="um_editor" value="' . $this->editor . '"/>';
        $html .= '<input type="hidden" id="um_common_nonce" value="' . $this->nonce . '"/>';
        // $html .= '<input type="hidden" name="form_action" value="' . $this->getFormAction() . '"/>';
        $html .= '<input type="hidden" name="current_query_string" value="' . esc_html($_SERVER['QUERY_STRING']) . '"/>';

        return $html;
    }

    /**
     * Determine form action from $_GET
     *
     * @return string new | edit | null
     */
    private function getFormAction()
    {
        return (string) filter_input(INPUT_GET, 'action');
    }

    function fieldsSelectorPanels()
    {
        global $userMeta;

        $fieldTypes = $userMeta->umFields();

        $nonce = wp_create_nonce('pf' . ucwords('add_field'));

        $fieldsGroup = array();
        foreach ($fieldTypes as $name => $field) {
            if (empty($field))
                continue;

            $disbled = ! $field['is_free'] && ! $userMeta->isPro() ? true : false;
            if ($disbled)
                continue;
            $button = $this->_createButton($name, $field['title'], array(
                'disable' => $disbled,
                'nonce' => $nonce
            ));

            if (isset($fieldsGroup[$field['field_group']]))
                $fieldsGroup[$field['field_group']] .= $button;
            else
                $fieldsGroup[$field['field_group']] = $button;
        }

        $fieldsGroupTitle = array(
            'wp_default' => __('WordPress Default Fields', $userMeta->name),
            // Changelog: From 2.4, Standard Fields changed to Basic Fields
            'standard' => __('Basic Fields', $userMeta->name),
            'advanced' => __('Advanced Fields', $userMeta->name),
            'formatting' => __('Formatting Fields', $userMeta->name)
        );

        foreach ($fieldsGroup as $key => $body) {
            $userMeta->buildPanel($fieldsGroupTitle[$key], $body);
        }
    }

    function sharedFieldsSelectorPanel()
    {
        global $userMeta;
        $fieldsType = $userMeta->umFields();

        $nonce = wp_create_nonce('pf' . ucwords('add_field'));

        $buttons = null;
        foreach ($this->sharedFields as $id => $field) {
            $typeTitle = $fieldsType[$field['field_type']]['title'];
            $label = 'ID:' . $id . ' (' . $typeTitle . ') ';
            if (! empty($field['field_title']))
                $label .= $field['field_title'];

            $hidden = isset($this->data['fields'][$id]) ? true : false;

            $buttons .= $this->_createButton($field['field_type'], esc_html($label), array(
                'id' => $id,
                'hidden' => $hidden,
                'nonce' => $nonce,
                'is_shared' => true
            ));
        }

        $userMeta->buildPanel(__('Shared Fields', $userMeta->name), $buttons);
    }

    private function _createButton($type, $label, $args = array())
    {
        $id = ! empty($args['id']) ? $args['id'] : 0;
        $class = ! empty($id) ? 'col-xs-12' : 'col-xs-5.5';
        $more = '';

        $btnClass = 'btn-default';
        if (! empty($args['is_shared'])) {
            $more .= ' data-is-shared="1"';
            $btnClass = 'btn-info';
        }

        if (! empty($args['disable'])) {
            $class = ' pf_blure';
            $more .= ' onclick="umGetProMessage(this)"';
        }

        if (! empty($args['hidden']))
            $more .= ' style="display:none"';

        return "<button type=\"button\" data-field-type=\"$type\" data-field-id=\"$id\"" . "data-nonce=\"{$args['nonce']}\" $more class=\"btn $btnClass um_field_selecor $class\" >$label</button>";
    }

    /**
     * Set for $_POST
     */
    function sanitizeFieldsIDs($fields)
    {
        if (! is_array($fields))
            return array();

        /**
         * Changing to array key
         */
        $sanitize = array();
        foreach ($fields as $field) {
            if (empty($field['id']))
                continue;

            $id = sanitize_key($field['id']);
            unset($field['id']);
            $sanitize[$id] = $field;
        }
        $fields = $sanitize;

        $sysMaxID = $this->getMaxFieldID();
        $formInitID = (int) sanitize_key($_POST['init_max_id']);
        $formMaxID = (int) sanitize_key($_POST['max_id']);

        if (($sysMaxID > $formInitID) && ($formMaxID > $formInitID)) {
            $diff = $sysMaxID - $formInitID;

            $sanitize = array();
            foreach ($fields as $id => $field) {
                if ($id > $formInitID)
                    $sanitize[$id + $diff] = $field;
                else
                    $sanitize[$id] = $field;
            }
            $fields = $sanitize;

            $this->maxID = $formMaxID + $diff;

            if (! empty($_SERVER['HTTP_REFERER'])) {
                $this->redirect_to = esc_url($_SERVER['HTTP_REFERER']);
            }
        } elseif ($formMaxID > $formInitID) {
            $this->maxID = $formMaxID;
        } else
            $this->maxID = 0;

        return $fields;
    }
}
