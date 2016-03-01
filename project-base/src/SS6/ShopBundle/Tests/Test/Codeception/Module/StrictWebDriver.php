<?php

namespace SS6\ShopBundle\Tests\Test\Codeception\Module;

use Codeception\Module\WebDriver;
use Codeception\Util\Locator;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\DomCrawler\Crawler;

class StrictWebDriver extends WebDriver {

	/**
	 * @param string[] $alternatives
	 * @return string
	 */
	private function getDeprecatedMethodExceptionMessage(array $alternatives) {
		$messageWithAlternativesPlaceholder = 'This method is deprecated because it uses fuzzy locators. '
			. 'Use one of strict alternatives instead: %s. Or implement new method with strict locator. See ' . self::class;

		return sprintf(
			$messageWithAlternativesPlaceholder,
			implode(', ', $alternatives)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function match($page, $selector, $throwMalformed = true) {
		if (!is_array($selector) && !$selector instanceof WebDriverBy) {
			$message = 'Using match() with fuzzy locator is slow. '
				. 'You should implement new method with strict locator. See ' . self::class;
			throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
		}
		return parent::match($page, $selector, $throwMalformed);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function findFields($selector) {
		if (!is_array($selector) && !$selector instanceof WebDriverElement) {
			$message = 'Using findFields() with fuzzy locator is slow. '
				. 'You should implement new method with strict locator. See ' . self::class;
			throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
		}
		return parent::findFields($selector);
	}

	/**
	 * @deprecated
	 */
	public function click($link, $context = null) {
		$strictAlternatives = [
			'clickBy*',
		];
		$message = $this->getDeprecatedMethodExceptionMessage($strictAlternatives);
		throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
	}

	/**
	 * @param string $text
	 * @param \Facebook\WebDriver\WebDriverBy|\Facebook\WebDriver\WebDriverElement|null $contextSelector
	 */
	public function clickByText($text, $contextSelector = null) {
		$locator = Crawler::xpathLiteral(trim($text));

		$xpath = Locator::combine(
			'.//a[normalize-space(.)=' . $locator . ']',
			'.//button[normalize-space(.)=' . $locator . ']',
			'.//a/img[normalize-space(@alt)=' . $locator . ']/ancestor::a',
			'.//input[./@type = "submit" or ./@type = "image" or ./@type = "button"][normalize-space(@value)=' . $locator . ']'
		);

		parent::click(['xpath' => $xpath], $contextSelector);
	}

	/**
	 * @param string $name
	 * @param \Facebook\WebDriver\WebDriverBy|\Facebook\WebDriver\WebDriverElement|null $contextSelector
	 */
	public function clickByName($name, $contextSelector = null) {
		$locator = Crawler::xpathLiteral(trim($name));

		$xpath = Locator::combine(
			'.//input[./@type = "submit" or ./@type = "image" or ./@type = "button"][./@name = ' . $locator . ']',
			'.//button[./@name = ' . $locator . ']'
		);

		parent::click(['xpath' => $xpath], $contextSelector);
	}

	/**
	 * @param string $css
	 */
	public function clickByCss($css) {
		parent::click(['css' => $css]);
	}

	/**
	 * @param \Facebook\WebDriver\WebDriverElement $element
	 * @return \Facebook\WebDriver\WebDriverElement
	 */
	public function clickByElement(WebDriverElement $element) {
		$element->click();
	}

	/**
	 * @deprecated
	 */
	public function fillField($field, $value) {
		$strictAlternatives = [
			'fillFieldBy*',
		];
		$message = $this->getDeprecatedMethodExceptionMessage($strictAlternatives);
		throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
	}

	/**
	 * @param \Facebook\WebDriver\WebDriverElement $element
	 * @param string $value
	 */
	public function fillFieldByElement(WebDriverElement $element, $value) {
		$element->clear();
		$element->sendKeys($value);
	}

	/**
	 * @param string $fieldName
	 * @param string $value
	 */
	public function fillFieldByName($fieldName, $value) {
		$locator = Crawler::xpathLiteral(trim($fieldName));
		$xpath = './/*[self::input | self::textarea | self::select][@name = ' . $locator . ']';

		parent::fillField(['xpath' => $xpath], $value);
	}

	/**
	 * @param string $css
	 * @param string $value
	 */
	public function fillFieldByCss($css, $value) {
		parent::fillField(['css' => $css], $value);
	}

	/**
	 * @param string $text
	 * @param string $css
	 */
	public function seeInCss($text, $css) {
		parent::see($text, ['css' => $css]);
	}

	/**
	 * @param string $text
	 * @param \Facebook\WebDriver\WebDriverElement $element
	 */
	public function seeInElement($text, WebDriverElement $element) {
		$this->assertContains($text, $element->getText());
	}

	/**
	 * @deprecated
	 */
	public function seeCheckboxIsChecked($checkbox) {
		$strictAlternatives = [
			'seeCheckboxIsCheckedBy*',
		];
		$message = $this->getDeprecatedMethodExceptionMessage($strictAlternatives);
		throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
	}

	/**
	 * @param string $checkboxId
	 */
	public function seeCheckboxIsCheckedById($checkboxId) {
		$locator = Crawler::xpathLiteral(trim($checkboxId));
		$xpath = './/input[@type = "checkbox"][./@id = ' . $locator . ']';

		parent::seeCheckboxIsChecked(['xpath' => $xpath]);
	}

	/**
	 * @deprecated
	 */
	public function dontSeeCheckboxIsChecked($checkbox) {
		$strictAlternatives = [
			'dontSeeCheckboxIsCheckedBy*',
		];
		$message = $this->getDeprecatedMethodExceptionMessage($strictAlternatives);
		throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
	}

	/**
	 * @param string $checkboxId
	 */
	public function dontSeeCheckboxIsCheckedById($checkboxId) {
		$locator = Crawler::xpathLiteral(trim($checkboxId));
		$xpath = './/input[@type = "checkbox"][./@id = ' . $locator . ']';

		parent::dontSeeCheckboxIsChecked(['xpath' => $xpath]);
	}

	/**
	 * @deprecated
	 */
	public function checkOption($option) {
		$strictAlternatives = [
			'checkOptionBy*',
		];
		$message = $this->getDeprecatedMethodExceptionMessage($strictAlternatives);
		throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
	}

	/**
	 * @param string $optionId
	 */
	public function checkOptionById($optionId) {
		$locator = Crawler::xpathLiteral(trim($optionId));
		$xpath = './/input[@type = "checkbox" or @type = "radio"][./@id = ' . $locator . ']';

		parent::checkOption(['xpath' => $xpath]);
	}

	/**
	 * @deprecated
	 */
	public function seeInField($field, $value) {
		$strictAlternatives = [
			'seeInFieldBy*',
		];
		$message = $this->getDeprecatedMethodExceptionMessage($strictAlternatives);
		throw new \SS6\ShopBundle\Tests\Test\Codeception\Exception\DeprecatedMethodException($message);
	}

	/**
	 * @param string $value
	 * @param string $fieldName
	 */
	public function seeInFieldByName($value, $fieldName) {
		$locator = Crawler::xpathLiteral(trim($fieldName));
		$xpath = './/*[self::input | self::textarea | self::select][@name = ' . $locator . ']';

		parent::seeInField(['xpath' => $xpath], $value);
	}

	/**
	 * @param string $value
	 * @param \Facebook\WebDriver\WebDriverElement $element
	 */
	public function seeInFieldByElement($value, WebDriverElement $element) {
		parent::seeInField($element, $value);
	}

}
