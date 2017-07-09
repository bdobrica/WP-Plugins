<?php
/**
 * Extension of SD_*
 */

/**
 * Access Control List Class
 *
 * @category
 * @package SalesDrive
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
class SD_ACL extends SD_Model {
	/**
	 * Version string a.b.c
	 * a # major release: paradigm changed
	 * b # minor release: added removed methods/properties/altered tables
	 * c # review: fixed bugs in already implemented methods/properties
	 * @var string
	 */
	public static $version = '1.0.0';

	/**
	 * Human redable name of this object
	 * @var string
	 */
	public static $human = 'ACLs';

	public static $T = 'sd_acls';

	public static $P = [
		'read',
		'write'
		];

	protected static $K = [
		'object_class',
		'object_id',
		'user_id',
		'allowed',
		'stamp'
		];

	protected static $Q = [
		'`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT',
		'`object_class` text NOT NULL',
		'`object_id` text NOT NULL',
		'`user_id` int(11) NOT NULL DEFAULT 0',
		'`allowed` ENUM (\'read\',\'write\') NOT NULL DEFAULT \'read\'',
		'`stamp` int(11) NOT NULL DEFAULT 0'
		];

	public static function grant ($permission, $object, $user) {
		if (!in_array ($permission, self::$P)) return FALSE;
		$object_class = get_class ($object);
		$object_id = $object->get ();
		$user_id = is_object ($user) ? $user->get () : $user;

		$acls = new SD_List ('SD_ACL', [
			sprintf ('object_class=\'%s\'', $object_class),
			sprintf ('object_id=\'%s\'', $object_id),
			sprintf ('user_id=%d', $user_id),
			sprintf ('allowed=\'%s\'', $permission)
			]);

		if (!$acls->is ('empty')) return TRUE;

		$acl = new SD_ACL ([
			'object_class'	=> $object_class,
			'object_id'	=> $object_id,
			'user_id'	=> $user_id,
			'allowed'	=> $permission,
			'stamp'		=> time ()
			]);

		try {
			$acl->save ();
			}
		catch (SD_Exception $e) {
			return FALSE;
			}
		return TRUE;
		}

	public static function revoke ($permission, $object, $user) {
		if (!in_array ($permission, self::$P)) return TRUE;
		$object_class = get_class ($object);
		$object_id = $object->get ();
		$user_id = is_object ($user) ? $user->get () : $user;

		$acls = new SD_List ('SD_ACL', [
			sprintf ('object_class=\'%s\'', $object_class),
			sprintf ('object_id=\'%s\'', $object_id),
			sprintf ('user_id=%d', $user_id),
			sprintf ('allowed=\'%s\'', $permission)
			]);

		if ($acls->is ('empty')) return TRUE;

		$out = TRUE;
		foreach ($acls->get () as $acl) {
			try {
				$acl->delete ();
				}
			catch (SD_Exception $e) {
				$out = FALSE;
				}
			}

		return $out;
		}

	public static function can ($permission, $object, $user) {
		if (!in_array ($permission, self::$P)) return TRUE;
		$object_class = get_class ($object);
		$object_id = $object->get ();
		$user_id = is_object ($user) ? $user->get () : $user;

		$acls = new SD_List ('SD_ACL', [
			sprintf ('object_class=\'%s\'', $object_class),
			sprintf ('object_id=\'%s\'', $object_id),
			sprintf ('user_id=%d', $user_id),
			sprintf ('allowed=\'%s\'', $permission)
			]);

		if ($acls->is ('empty')) return FALSE;
		return TRUE;
		}
	}
?>
