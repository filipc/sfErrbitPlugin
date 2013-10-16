<?php

/**
 * Errbit PHP Notifier.
 *
 * Copyright © Flippa.com Pty. Ltd.
 * See the LICENSE file for details.
 */

/**
 * Like Nokogiri, but shittier.
 *
 * Lambdas are used to construct a full tree of XML.
 *
 * @example
 *
 *   $builder = new Errbit_XmlBuilder();
 *   $builder->tag('product', function($product) {
 *     $product->tag('name', 'Plush Puppy Toy');
 *     $product->tag('price', '$8', array('currency' => 'USD'));
 *     $product->tag('discount', function($discount) {
 *       $discount->tag('percent', 20);
 *       $discount->tag('name',    '20% off promotion');
 *     });
 *   })
 *   ->asXml();
 */
class Errbit_XmlBuilder {
	/**
	 * [DOMElement] that this Builder instance represents.
	 */
	protected $_xml;

	/**
	 * [DOMDocument] that this Element is a descendent of
	 */
	protected $_doc;

	/**
	 * Instantiate a new XmlBuilder.
	 *
	 * @param [SimpleXMLElement] $xml
	 *   the parent node (only used internally)
	 */
	public function __construct($xml = null) {
		if ($xml) {
			$this->_xml = $xml;
		} else {
			$this->_doc = $this->_xml = new DOMDocument('1.0', 'UTF-8');
		}
	}

	/**
	 * Insert a tag into the XML.
	 *
	 * @param [String] $name
	 *   the name of the tag, required.
	 *
	 * @param [String] $value
	 *   the text value of the element, optional
	 *
	 * @param [Array] $attributes
	 *   an array of attributes for the tag, optional
	 *
	 * @param [Callable] $callback
	 *   a callback to receive an XmlBuilder for the new tag, optional
	 *
	 * @return [XmlBuilder]
	 *   a builder for the inserted tag
	 */
	public function tag($name /* , $value, $attributes, $callback */) {
		$value      = '';
		$attributes = array();
		$callback   = null;
		$args       = func_get_args();

		array_shift($args);
		foreach ($args as $arg) {
			if (is_a($arg, 'Closure')) {
				$callback = $arg;
			} elseif (is_array($arg)) {
				$attributes = $arg;
			} elseif (is_scalar($arg)) {
				$value = (string)$arg;
			} elseif (is_object($arg)) {
				$value = "[" . get_class($arg) . "]";
			}
		}

		$tag = $this->_doc->createElement($name,
		 htmlspecialchars($value, ENT_QUOTES));
		$builder = new self($tag);
		$builder->_doc = $this->_doc;

		foreach ($attributes as $attr => $v) {
			$tag->setAttribute($attr, $v);
		}
		$this->_xml->appendChild($tag);

		if ($callback) {
			$callback($builder);
		}

		return $builder;
	}

	/**
	 * Add an attribute to the current element.
	 *
	 * @param [String] $name
	 *   the name of the attribute
	 *
	 * @param [String] $value
	 *   the value of the attribute
	 *
	 * @return [XmlBuilder]
	 *   the current builder
	 */
	public function attribute($name, $value) {
		$this->_xml->setAttribute($name, $value);
		return $this;
	}

	/**
	 * Return this XmlBuilder as a string of XML.
	 *
	 * @return [String]
	 *   the XML of the document
	 */
	public function asXml() {
		return $this->_doc->saveXML($this->_xml);
	}
}
