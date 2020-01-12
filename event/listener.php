<?php
/**
*
* @package phpBB Extension - Copyright in footer
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\copyright\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	*
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\user $user
	)
	{
		$this->config 	= $config;
		$this->template = $template;
		$this->user		= $user;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'		=> 'load_language_on_setup',
			'core.page_footer'		=> 'page_footer',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dmzx/copyright',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function page_footer($event)
	{
		$start_date = @gmdate('Y', $this->config['board_startdate']);
		$year_date = (@gmdate('Y') - @gmdate('Y', $this->config['board_startdate']));

		$this->template->assign_vars(array(
			'L_COPYRIGHT_YEAR'	=>	$start_date,
			'COPYRIGHT_YEAR'	=>	$start_date,
			'L_COPYRIGHT_YEARS'	=>	$this->user->lang('COPYRIGHT_YEAR_ACTIVE', $year_date),
		));
	}
}
