<?php
namespace UserMeta\Field;

/**
 * Handle section_heading field.
 * Direct Child: PageHeading
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class SectionHeading extends Base
{

    protected $inSection;

    protected function setExtra(array $extra)
    {
        parent::setExtra($extra);

        if (isset($extra['inSection'])) {
            $this->inSection = $extra['inSection'];
        }
    }

    /**
     * Add um_group_segment
     */
    protected function configure_()
    {
        $this->containerAttr['class'] .= ' um_group_segment';
    }

    protected function toString(array $attributes)
    {
        $string = '';
        foreach ($attributes as $key => $val) {
            $string .= " $key=\"$val\"";
        }

        return $string;
    }

    public function render()
    {
        if (! $this->isQualified)
            return;

        $html = null;
        if ($this->inSection)
            $html .= "</div>";

        $html .= '<div ' . $this->toString($this->containerAttr) . '>';
        if ($this->label)
            $html .= "<h4>{$this->label}</h4>";
        $html .= $this->renderDescription();
        if (isset($this->field['show_divider']))
            $html .= '<div class="pf_divider"></div>';
        $html .= $this->getConditionScript();

        return $html;
    }
}