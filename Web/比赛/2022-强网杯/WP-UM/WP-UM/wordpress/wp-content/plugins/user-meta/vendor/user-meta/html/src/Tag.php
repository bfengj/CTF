<?php
namespace UserMeta\Html;

/*
 * trait for html elements
 *
 * @author Khaled Hossain
 * @since 1.0.0
 */
trait Tag
{

    /**
     * Generate meta tag.
     *
     * @param array $attributes:            
     * @return string html
     */
    protected function meta(array $attributes = [])
    {
        return $this->singleTag('meta', $attributes);
    }

    /**
     * Generate img tag.
     *
     * @param array $attributes:            
     * @return string html
     */
    protected function img(array $attributes = [])
    {
        return $this->singleTag('img', $attributes);
    }

    /**
     * Generate base tag.
     *
     * @param array $attributes:            
     * @return string html
     */
    protected function base(array $attributes = [])
    {
        return $this->singleTag('base', $attributes);
    }

    /**
     * Generate link tag.
     *
     * @param array $attributes:            
     * @return string html
     */
    protected function link(array $attributes = [])
    {
        return $this->singleTag('link', $attributes);
    }

    /**
     * Generate frame tag.
     *
     * @param array $attributes:            
     * @return string html
     */
    protected function frame(array $attributes = [])
    {
        return $this->singleTag('frame', $attributes);
    }

    /**
     * Generate single line tag
     *
     * @param string $type            
     * @param array $attributes            
     * @return string html
     */
    protected function singleTag($type, array $attributes = [])
    {
        $this->setProperties($type, '', $attributes);
        
        return $this->_publish("<{$type}{$this->attributes()}/>");
    }

    /**
     * Generate html tag.
     *
     * @param string $type:
     *            tag type, e.g. p, div, label
     * @param string $default:
     *            Text for element
     * @param array $attributes:
     *            (optional)
     *            
     * @return string : html
     */
    protected function tag($type, $default = null, array $attributes = [])
    {
        $this->setProperties($type, $default, $attributes);
        
        return $this->_publish("<{$type}{$this->attributes()}>$default</$type>");
    }
}