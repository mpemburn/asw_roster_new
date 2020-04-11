<?php

namespace App\Helpers;

class Utility {

	public static function formatDate($mask, $date)
	{
		return (empty($date)) ? $date : date($mask, strtotime($date));
	}

	public static function formatMjY($date)
	{
		return self::formatDate('M j, Y', $date);
	}

	public static function formatPhone($phone_number)
	{
		$phone = preg_replace('/[^0-9]/', '', $phone_number);
		if(strlen($phone) == 7) {
			return preg_replace( "/(\d{3})(\d{4})/", "$1-$2", $phone );
		} elseif(strlen($phone) == 10) {
			return preg_replace( "/(\d{3})(\d{3})(\d{4})/", "($1) $2-$3", $phone );
		} elseif(strlen($phone) > 10) {
			$ext_test = '(\d{' . (strlen($phone) - 10) . '})';
			return preg_replace( "/(\d{3})(\d{3})(\d{4})$ext_test/", "($1) $2-$3 ext $4", $phone );
		} else {
			return $phone;
		}
	}

	public static function isDate($date, $format = 'Y-m-d')
	{
		$d = \DateTime::createFromFormat($format, $date);

		return $d && $d->format($format) === $date;
	}

	public static function mailto($address_list, $limit = 1)
	{
		$mailtos = array();
		$count = 1;
		$addresses = explode(',', $address_list);
		foreach ($addresses as $address) {
			$address = trim($address);
			$mailtos[] = '<a href="mailto:' . $address . '">' . $address . '</a>';
			if ($count >= $limit) {
				break;
			}
			$count++;
		}
		return implode(', ', $mailtos);
	}

	public static function ordinal($number)
	{
		if ($number < 1) {
			return '';
		}
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13)) {
			return $number . 'th';
		} else {
			return $number. $ends[$number % 10];
		}
	}

	public static function reformatCheckboxes($data, $field_names)
	{
		$up_data = $data;
		foreach ($field_names as $field_name) {
			$up_data[$field_name] = (isset($data[$field_name])) ? 1 : 0;
		}
		return $up_data;
	}

	public static function reformatDates($data, $field_names, $mask)
	{
		$up_data = $data;
		foreach ($data as $field => $value) {
			if (in_array($field, $field_names)) {
				$up_data[$field] = (!is_null($value) && !empty($value)) ? date($mask, strtotime($value)) : null;
			}
		}
		return $up_data;
	}

	public static function yesno($int_or_bool)
	{
		return ($int_or_bool) ? 'Yes' : 'No';
	}
}