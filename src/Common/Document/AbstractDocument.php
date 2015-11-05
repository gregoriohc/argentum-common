<?php namespace Argentum\Common\Document;

use Argentum\Common\Exception\InvalidDocumentException;
use Argentum\Common\Parametrized;
use League\Plates\Engine as TemplateEngine;

/**
 * Document class
 *
 * This class defines and abstracts all of the document types used
 * throughout the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define document parameters, which should look like this
 *   $parameters = [
 *       'type' => 'invoice',
 *       'content' => [
 *           'sectionFirst' => [
 *               'parameterOne' => 'Abcd',
 *               'parameterTwo' => '123456789',
 *           ],
 *           'sectionSecond' => [
 *               'parameterOne'   => 'Abcd',
 *               'parameterTwo'   => 123.45,
 *               'parameterThree' => false',
 *           ],
 *       ],
 *   ];
 *
 *   // Create a document object
 *   $document = new Document($parameters);
 * </code>
 *
 * The full list of document attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * type
 * * content
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
abstract class AbstractDocument extends Parametrized implements DocumentInterface
{
    private $templateEngine;

    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('type'));

        if (!isset($parameters['content'])) {
            $parameters['content'] = [];
        }

        $this->templateEngine = new TemplateEngine(__DIR__.'/views');

        parent::__construct($parameters);
    }

    /**
     * Validate this document. If the document is invalid, InvalidDocumentException is thrown.
     *
     * @throws InvalidDocumentException
     * @return void
     */
    public function validate()
    {
        parent::validate();

        if (!is_string($this->getType())) {
            throw new InvalidDocumentException("The type parameter must be a string");
        }
    }

    /**
     * Get document type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Set document type
     *
     * @param string $value Parameter value
     * @return AbstractDocument provides a fluent interface.
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get document content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->getParameter('content');
    }

    /**
     * Set document content
     *
     * @param array $value Parameter value
     * @return AbstractDocument provides a fluent interface.
     */
    public function setContent($value)
    {
        return $this->setParameter('content', $value);
    }

    /**
     * Add templates folder
     *
     * @param string $name
     * @param string $path
     */
    public function addTemplatesFolder($name, $path)
    {
        if (is_dir($path)) {
            $this->templateEngine->addFolder($name, $path);
        }
    }

    /**
     * @param string $format
     * @param array $parameters
     * @param null|string $folderName
     * @return string
     */
    public function render($format, $parameters = array(), $folderName = null)
    {
        $this->validate();

        $view = $this->getType().'-'.$format;

        if (null !== $folderName) {
            $view = $folderName.'::'.$view;
        } else {
            if ($this->templateEngine->getFolders()->exists('custom') && $this->templateEngine->exists('custom::'.$view)) {
                $view = 'custom::'.$view;
            } elseif ($this->templateEngine->getFolders()->exists('gateway') && $this->templateEngine->exists('gateway::'.$view)) {
                $view = 'gateway::'.$view;
            }
        }
        return $this->templateEngine->render($view, array_merge([$this->getType() => $this], $parameters));
    }

}
