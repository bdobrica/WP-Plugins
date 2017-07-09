<?php
/**
 * Core of CoreSite
 */
namespace CoreSite\Core;
/**
 * Abstract class for defining connections between objects and database tables.
 *
 * @category Person
 * @package CoreSite
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class Person extends Model {
	public static $version = '1.0.0';
	public static $human = 'Person';
	public static $T = 'persons';
	protected static $K = [
		'first_name',
		'last_name',
		'birthday',
		'email',
		'phone',
		'house',
		'budget',
		'address',
		'visits',
		'registered',
		'stamp'
		];
	protected static $Q = [
		
		];
	}
