<?php

class BBCodeParser extends CApplicationComponent
{
	/**
	 * @property array the regex for bold to html.
	 */
	public $boldToHtml = array(
		'/\[b\](.*?)\[\/b\]/i' => '<strong>$1</strong>'
	);
	/**
	 * @property array the regex for italic to html.
	 */
	public $italicToHtml = array(
		'/\[i\](.*?)\[\/i\]/i' => '<em>$1</em>'
	);
	/**
	 * @property array the regex for underline to html.
	 */
	public $underlineToHtml = array(
		'/\[u\](.*?)\[\/u\]/i' => '<u>$1</u>'
	);
	/**
	 * @property array the regex for image to html.
	 */
	public $imageToHtml = array(
		'/\[img\](.*?)\[\/img\]/i' => '<img src="$1" />'
	);
	/**
	 * @property array the regex for link to html.
	 */
	public $linkToHtml = array(
		'/\[url(?:\=?)(.*?)\](.*?)\[\/url\]/i' => '<a href="$1">$2</a>'
	);
	/**
	 * @property array the regex for code to html.
	 */
	public $codeToHtml = array(
		'/\[code\]\s*(.*?)\s*\[\/code\]/i' => '<pre>$1</pre>'
	);
	/**
	 * @property array the regex for quote to html.
	 */
	public $quoteToHtml = array(
		'/\[quote(?:\=?)(.*?)\]\s*(.*?)\s*\[\/quote\]/i' => '<blockquote><p>$1</p>$2</blockquote>'
	);
	/**
	 * @property array the regex for line break to html.
	 */
	public $linebreakToHtml = array(
		'/(\\r\\n|\\n\\r|\\n|\\r)/i' => '<br />'
	);
	/**
	 * @property array the regex for custom to html.
	 */
	public $customToHtml = array();

	/**
	 * @property array the regex for bold to bbcode.
	 */
	public $boldFromHtml = array(
		'/\<strong\>(.*?)\<\/strong\>/i' => '[b]$1[/b]'
	);
	/**
	 * @property array the regex for italic to bbcode.
	 */
	public $italicFromHtml = array(
		'/\<em\>(.*?)\<\/em\>/i' => '[i]$1[/i]'
	);
	/**
	 * @property array the regex for underline to bbcode.
	 */
	public $underlineFromHtml = array(
		'/\<u\>(.*?)\<\/u\>/i' => '[u]$1[/u]',
	);
	/**
	 * @property array the regex for image to bbcode.
	 */
	public $imageFromHtml = array(
		'/\<img src="(.*?)" \/\>/i' => '[img]$1[/img]'
	);
	/**
	 * @property array the regex for link to bbcode.
	 */
	public $linkFromHtml = array(
		'/\<a href="(.*?)"\>(.*?)\<\/a\>/i' => '[url=$1]$2[/url]' // TODO: fix the issues with this one
	);
	/**
	 * @property array the regex for code to bbcode.
	 */
	public $codeFromHtml = array(
		'/\<pre\>(.*?)\<\/pre\>/i' => '[code]$1[/code]'
	);
	/**
	 * @property array the regex for quote to bbcode.
	 */
	public $quoteFromHtml = array(
		'/\<blockquote\>\<p\>(.*?)\<\/p\>(.*?)\<\/blockquote\>/i' => '[quote=$1]$2[/quote]'
	);
	/**
	 * @property array the regex for line break to bbcode.
	 */
	public $linebreakFromHtml = array(
		'/\<br \/\>/i' => PHP_EOL
	);
	/**
	 * @property array the regex for custom to bbcode.
	 */
	public $customFromHtml = array();

	/**
	 * Converts BBCode into HTML.
	 * @param string $markup the markup to be converted.
	 * @return string the markup.
	 */
	public function bbcode2html($markup)
	{
		$regex = array(
			$this->boldToHtml,
			$this->italicToHtml,
			$this->underlineToHtml,
			$this->imageToHtml,
			$this->linkToHtml,
			$this->codeToHtml,
			$this->quoteToHtml,
			$this->linebreakToHtml,
		);

		$regex = array_merge($this->customToHtml, $regex);

		return $this->replace($markup, $regex);
	}

	/**
	 * Converts HTML into BBCode.
	 * @param string $markup the markup to be converted.
	 * @return string the markup.
	 */
	public function html2bbcode($markup)
	{
		$regex = array(
			$this->boldFromHtml,
			$this->italicFromHtml,
			$this->underlineFromHtml,
			$this->imageFromHtml,
			$this->linkFromHtml,
			$this->codeFromHtml,
			$this->quoteFromHtml,
			$this->linebreakFromHtml,
		);

		$regex = array_merge($this->customFromHtml, $regex);

		return $this->replace($markup, $regex);
	}

	/**
	 * Replaces markup with a specific regex.
	 * @param string $markup the replace context.
	 * @param array $regex the regex to replace with in the format (search=>replace)
	 * @return string the replaced markup.
	 */
	protected function replace($markup, $regex)
	{
		foreach ($regex as $pair)
			foreach ($pair as $search => $replace)
				$markup = preg_replace($search, $replace, $markup);

		return $markup;
	}
}