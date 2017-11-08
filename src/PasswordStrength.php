<?php

/**
 * @author Vassilis Kanellopoulos <contact@kanellov.com>
 * @created Oct 17, 2013
 */

namespace Knlv\Validator;

use Zend\Validator\AbstractValidator;

class PasswordStrength extends AbstractValidator {
	const MUST_CONTAIN_NUMBER = 'number';
	const MUST_CONTAIN_LOWERCASE = 'lower';
	const MUST_CONTAIN_UPPERCASE = 'upper';
	const MUST_CONTAIN_SYMBOL = 'symbol';
	const MUST_CONTAIN_NUMBER_OR_SYMBOL = 'numberofsymbol';

	const NO_NUMBER = 'noNumber';
	const NO_LOWERCASE = 'noLowerCase';
	const NO_UPPERCASE = 'noUpperCase';
	const NO_SYMBOL = 'noSymbol';
	const NO_NUMBER_OR_SYMBOL = 'noNumberOfSymbol';

	protected $messageTemplates = array(
		self::NO_NUMBER => 'Password must contain at least one number character',
		self::NO_LOWERCASE => 'Password must contain at least one lower case character',
		self::NO_UPPERCASE => 'Password must contain at least one UPPER CASE character',
		self::NO_SYMBOL => 'Password must contain at least one symbol character',
		self::NO_NUMBER_OR_SYMBOL => 'Password must contain at least one number or symbol character',
	);
	protected $maxLength = 8;
	protected $charlist = '!@$-1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	protected $mustContain = array(
		self::MUST_CONTAIN_LOWERCASE => true,
		self::MUST_CONTAIN_UPPERCASE => true,
		self::MUST_CONTAIN_NUMBER => false,
		self::MUST_CONTAIN_SYMBOL => false,
		self::MUST_CONTAIN_NUMBER_OR_SYMBOL => true,
	);

	public function __construct($options = null) {
		parent::__construct($options);
		if (isset($options['max_length'])) {
			$this->setMaxLength($options['max_length']);
		}
		if (isset($options['char_list'])) {
			$this->setCharlist($options['char_list']);
		}
		if (isset($options['must_contain'])) {
			$this->setMustContain($options['must_contain']);
		}
	}

	public function getMaxLength() {
		return $this->maxLength;
	}

	public function setMaxLength($maxLength) {
		$this->maxLength = (int) $maxLength;

		return $this;
	}

	public function getCharlist() {
		return $this->charlist;
	}

	public function setCharlist($charlist) {
		$this->charlist = (string) $charlist;

		return $this;
	}

	public function getMustContain() {
		return $this->mustContain;
	}

	public function setMustContain(array $mustContain) {
		foreach ($mustContain as $key => $value) {
			if (array_key_exists($key, $this->mustContain)) {
				$this->mustContain[$key] = (bool) $value;
			}
		}

		return $this;
	}

	public function isValid($value) {
		$symbols = array();
		$countSymbols = preg_match_all(
			'/[^a-z0-9]/i',
			$this->getCharlist(),
			$symbols
		);
		if ($countSymbols > 0) {
			$symbols = $symbols[0];
		}
		$symbols = implode('', $symbols);

		if (preg_match('/[a-z]/', $value) !== 1
			&& $this->mustContain[self::MUST_CONTAIN_LOWERCASE]) {
			$this->error(self::NO_LOWERCASE);

			return false;
		}

		if (preg_match('/[A-Z]/', $value) !== 1
			&& $this->mustContain[self::MUST_CONTAIN_LOWERCASE]) {
			$this->error(self::NO_UPPERCASE);

			return false;
		}

		if ($symbols && preg_match('/[0-9]|[' . $symbols . ']/', $value) !== 1
			&& $this->mustContain[self::MUST_CONTAIN_NUMBER_OR_SYMBOL]) {
			$this->error(self::NO_NUMBER_OR_SYMBOL);

			return false;
		}

		if (preg_match('/[0-9]/', $value) !== 1
			&& $this->mustContain[self::MUST_CONTAIN_NUMBER]) {
			$this->error(self::NO_NUMBER);

			return false;
		}

		if ($symbols && preg_match('/[' . $symbols . ']/', $value) !== 1
			&& $this->mustContain[self::MUST_CONTAIN_SYMBOL]) {
			$this->error(self::NO_SYMBOL);

			return false;
		}

		return true;
	}
}
