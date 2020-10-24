<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

class plgOSMembershipDOcuments extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Path to the folder which documents are store
	 *
	 * @var string
	 */
	protected $documentsPath;

	/**
	 * Path to the folder store update packages
	 *
	 * @var bool
	 */
	protected $updatePackagesPath;

	/**
	 * Plugin constructor.
	 *
	 * @param object $subject
	 * @param array  $config
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$path = $this->params->get('documents_path', 'media/com_osmembership/documents');

		if (JFolder::exists(JPATH_ROOT . '/' . $path))
		{
			$this->documentsPath = JPATH_ROOT . '/' . $path;
		}
        elseif (JFolder::exists($path))
		{
			$this->documentsPath = $path;
		}
		else
		{
			throw new InvalidArgumentException(sprintf('Invalid documents path %s', $path));
		}

		if (JFolder::exists($this->documentsPath . '/update_packages'))
		{
			$this->updatePackagesPath = $this->documentsPath . '/update_packages';
		}
	}

	/**
	 * Render setting form
	 *
	 * @param PlanOSMembership $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_clean();

		return array('title' => JText::_('OSM_DOWNLOADS_MANAGER'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database, in this case, use params field of plans table
	 *
	 * @param OSMembershipTablePlan $row
	 * @param bool                  $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$query = $this->db->getQuery(true);

		$documentIds = isset($data['document_id']) ? $data['document_id'] : [];
		$documentIds = ArrayHelper::toInteger($documentIds);

		// Remove the removed documents
		if (!$isNew)
		{
			$query->delete('#__osmembership_documents')
				->where('plan_id = ' . (int) $row->id);

			if (count($documentIds))
			{
				$query->where('id NOT IN (' . implode(',', $documentIds) . ')');
			}

			$this->db->setQuery($query);
			$this->db->execute();
		}

		//save new data
		if (isset($data['document_title']))
		{
			$pathUpload           = JPath::clean($this->documentsPath . '/');
			$documentIds          = $data['document_id'];
			$documentTitles       = $data['document_title'];
			$documentAttachments  = $_FILES['document_attachment'];
			$availableAttachments = $data['document_available_attachment'];
			$updatePackages       = isset($data['update_package']) ? $data['update_package'] : [];
			$orderings            = $data['document_ordering'];

			for ($i = 0; $n = count($documentTitles), $i < $n; $i++)
			{
				$documentTitle = $documentTitles[$i];

				if (empty($documentTitle))
				{
					continue;
				}

				$attachmentsFileName = '';

				if (is_uploaded_file($documentAttachments['tmp_name'][$i]))
				{
					$attachmentsFileName = JFile::makeSafe($documentAttachments['name'][$i]);
					JFile::upload($documentAttachments['tmp_name'][$i], $pathUpload . $attachmentsFileName, false, true);
				}

				$documentId    = (int) $documentIds[$i];
				$documentTitle = $this->db->quote($documentTitle);
				$ordering      = (int) $orderings[$i];

				if (!$attachmentsFileName)
				{
					$attachmentsFileName = $availableAttachments[$i];
				}

				$attachmentsFileName = $this->db->quote($attachmentsFileName);
				$updatePackage       = isset($updatePackages[$i]) ? $updatePackages[$i] : '';
				$updatePackage       = $this->db->quote($updatePackage);

				if ($documentId)
				{
					$query->clear()
						->update('#__osmembership_documents')
						->set('ordering =' . $ordering)
						->set('title = ' . $documentTitle)
						->set('attachment = ' . $attachmentsFileName)
						->set('update_package = ' . $updatePackage)
						->where('id = ' . $documentId);
				}
				else
				{
					$query->clear()
						->insert('#__osmembership_documents')
						->columns('plan_id, ordering, title, attachment, update_package')
						->values("$row->id,$ordering,$documentTitle,$attachmentsFileName, $updatePackage");
				}

				$this->db->setQuery($query);
				$this->db->execute();
			}
		}

		// Clear data in plan documents table
        $query->clear()
            ->delete('#__osmembership_plan_documents')
            ->where('plan_id = '. $row->id);
		$this->db->setQuery($query);
		$this->db->execute();

		$sql = 'INSERT INTO #__osmembership_plan_documents(plan_id, document_id) SELECT plan_id, id FROM #__osmembership_documents WHERE plan_id = '.$row->id;
		$this->db->setQuery($sql)
            ->execute();

		if (!empty($data['existing_document_ids']))
		{
			$documentIds = array_filter(ArrayHelper::toInteger($data['existing_document_ids']));

			if (count($documentIds))
			{
				$query->clear()
					->insert('#__osmembership_plan_documents')
					->columns($this->db->quoteName(['plan_id', 'document_id']));

				foreach ($documentIds as $documentId)
				{
					$query->values(implode(',', [$row->id, $documentId]));
				}

				$this->db->setQuery($query)
					->execute();
			}
		}
	}

	/**
	 * Render setting form
	 *
	 * @param JTable $row
	 *
	 * @return array
	 */
	public function onProfileDisplay($row)
	{
		ob_start();
		$this->drawDocuments($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('OSM_MY_DOWNLOADS'),
		             'form'  => $form,
		);
	}

	/**
	 * Display list of files which users can choose for event attachment
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	protected function getAttachmentList($path)
	{
		$path      = JPath::clean($path);
		$files     = JFolder::files($path);
		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_DOCUMENT'));

		for ($i = 0, $n = count($files); $i < $n; $i++)
		{
			$file      = $files[$i];
			$options[] = JHtml::_('select.option', $file, $file);
		}

		return $options;
	}

	/**
	 * Display form allows users to change settings on subscription plan add/edit screen
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		$query = $this->db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_documents')
			->order('ordering')
			->where('plan_id=' . (int) $row->id)
			->order('ordering');
		$this->db->setQuery($query);
		$documents = $this->db->loadObjectList();

		$options = $this->getAttachmentList($this->documentsPath);

		if ($this->updatePackagesPath)
		{
			$updatePackages = $this->getAttachmentList($this->updatePackagesPath);
		}
		else
		{
			$updatePackages = [];
		}

		$supportJoomlaUpdate = count($updatePackages);

		// Get the selected existing documents for this plan
        if ($row->id)
        {
	        $query->clear()
		        ->select('document_id')
		        ->from('#__osmembership_plan_documents')
		        ->where('plan_id = '. (int) $row->id);
	        $this->db->setQuery($query);
	        $planExistingDocumentIds = $this->db->loadColumn();
        }
        else
        {
	        $planExistingDocumentIds = [];
        }

		// Get list of existing documents which can be selected for this plan
		$query->clear()
			->select('id, title')
			->from('#__osmembership_documents');

        if ($row->id)
        {
            $query->where('plan_id != '. (int) $row->id);
        }

        $this->db->setQuery($query);
		$existingDocuments = $this->db->loadObjectList();
		?>
        <div class="row-fluid">
            <table class="adminlist table table-striped">
                <tr>
                    <td width="20%">
				        <?php echo JText::_('OSM_CHOOSE_EXISTING_DOCUMENTS'); ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('select.genericlist', $existingDocuments, 'existing_document_ids[]', 'class="advSelect input-xlarge" multiple', 'id', 'title', $planExistingDocumentIds); ?>
                    </td>
                </tr>
            </table>
            <table class="adminlist table table-striped" id="adminForm">
                <thead>
                <tr>
                    <th class="nowrap center"><?php echo JText::_('ID'); ?></th>
                    <th class="nowrap center"><?php echo JText::_('OSM_TITLE'); ?></th>
                    <th class="nowrap center"><?php echo JText::_('OSM_ORDERING'); ?></th>
                    <th class="nowrap center"><?php echo JText::_('OSM_DOCUMENT'); ?></th>
					<?php
					if ($supportJoomlaUpdate)
					{
						?>
                        <th class="nowrap center"><?php echo JText::_('OSM_UPDATE_PACKAGE'); ?></th>
						<?php
					}
					?>
                    <th class="nowrap center"><?php echo JText::_('OSM_REMOVE'); ?></th>
                </tr>
                </thead>
                <tbody id="additional_documents">
				<?php
				for ($i = 0; $i < count($documents); $i++)
				{
					$document = $documents[$i];
					?>
                    <tr id="document_<?php echo $i; ?>">
                        <td class="center">
							<?php if ($document->id) echo $document->id; ?>
                            <input type="hidden" name="document_id[]" value="<?php echo $document->id; ?>"/>
                        </td>
                        <td><input type="text" class="input-xlarge" name="document_title[]"
                                   value="<?php echo $document->title; ?>"/></td>
                        <td><input type="text" class="input-mini" name="document_ordering[]"
                                   value="<?php echo $document->ordering; ?>"/></td>
                        <td><input type="file" name="document_attachment[]"
                                   value=""><?php echo JHtml::_('select.genericlist', $options, 'document_available_attachment[]', 'class="input-xlarge"', 'value', 'text', $document->attachment); ?>
                        </td>
						<?php
						if ($supportJoomlaUpdate)
						{
							?>
                            <td><?php echo JHtml::_('select.genericlist', $updatePackages, 'update_package[]', 'class="input-xlarge"', 'value', 'text', $document->update_package); ?></td>
							<?php
						}
						?>
                        <td>
                            <button type="button" class="btn btn-danger"
                                    onclick="removeDocument(<?php echo $i; ?>)"><i
                                        class="icon-remove"></i><?php echo JText::_('OSM_REMOVE'); ?></button>
                        </td>
                    </tr>
					<?php
				}
				?>
                </tbody>
            </table>
            <button type="button" class="btn btn-success" onclick="adddocument()"><i
                        class="icon-new icon-white"></i><?php echo JText::_('OSM_ADD'); ?></button>
        </div>
        <script language="JavaScript">
            (function ($) {
                removeDocument = (function (id) {
                    if (confirm('<?php echo JText::_('OSM_REMOVE_ITEM_CONFIRM', true); ?>')) {
                        $('#document_' + id).remove();
                    }
                })
                var countDocument = <?php echo count($documents) ?>;
                adddocument = (function () {
                    var html = '<tr id="document_' + countDocument + '">'
                    html += '<td><input type="hidden" name="document_id[]" value="0" /></td>';
                    html += '<td><input type="text" class="input-xlarge" name="document_title[]" value="" /><input type="hidden" name="document_id[]" value="0" /></td>';
                    html += '<td><input type="text" class="input-mini" name="document_ordering[]" value="" /></td>';
                    html += '<td><input type="file" name="document_attachment[]" value=""><?php echo preg_replace(array('/\r/', '/\n/'), '', addslashes(JHtml::_('select.genericlist', $options, 'document_available_attachment[]', 'class="input-xlarge"', 'value', 'text', ''))); ?></td>';
					<?php
					if ($supportJoomlaUpdate)
					{
					?>
                    html += '<td><?php echo preg_replace(array('/\r/', '/\n/'), '', addslashes(JHtml::_('select.genericlist', $updatePackages, 'update_package[]', 'class="input-xlarge"', 'value', 'text', ''))); ?></td>';
					<?php
					}
					?>
                    html += '<td><button type="button" class="btn btn-danger" onclick="removeDocument(' + countDocument + ')"><i class="icon-remove"></i><?php echo JText::_('OSM_REMOVE'); ?></button></td>';
                    html += '</tr>';
                    $('#additional_documents').append(html);
                    countDocument++;
                })
            })(jQuery)
        </script>
		<?php
	}

	/**
	 * Display Display List of Documents which the current subscriber can download from his subscription
	 *
	 * @param object $row
	 */
	private function drawDocuments($row)
	{
		$query         = $this->db->getQuery(true);
		$activePlanIds = OSMembershipHelper::getActiveMembershipPlans();
		$query->select('a.*')
			->from('#__osmembership_documents AS a')
			->where('a.id IN (SELECT document_id FROM #__osmembership_plan_documents AS b WHERE b.plan_id  IN (' . implode(',', $activePlanIds) . ') )')
			->order('a.ordering');
		$this->db->setQuery($query);
		$documents = $this->db->loadObjectList();

		if (empty($documents))
		{
			return;
		}

		$Itemid = JFactory::getApplication()->input->getInt('Itemid');
		$path   = JPath::clean($this->documentsPath . '/');

		$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		$centerClass = $bootstrapHelper->getClassMapping('center');
		?>
        <table class="adminlist <?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>" id="adminForm">
            <thead>
            <tr>
                <th class="title"><?php echo JText::_('OSM_TITLE'); ?></th>
                <th class="title"><?php echo JText::_('OSM_DOCUMENT'); ?></th>
                <th class="<?php echo $centerClass; ?>"><?php echo JText::_('OSM_SIZE'); ?></th>
                <th class="<?php echo $centerClass; ?>"><?php echo JText::_('OSM_DOWNLOAD'); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			for ($i = 0; $i < count($documents); $i++)
			{
				$document     = $documents[$i];
				$downloadLink = JRoute::_('index.php?option=com_osmembership&task=download_document&id=' . $document->id . '&Itemid=' . $Itemid);
				?>
                <tr>
                    <td><a href="<?php echo $downloadLink ?>"><?php echo $document->title; ?></a></td>
                    <td><?php echo $document->attachment; ?></td>
                    <td class="<?php echo $centerClass; ?>"><?php echo OSMembershipHelperHtml::getFormattedFilezize($path . $document->attachment); ?></td>
                    <td class="<?php echo $centerClass; ?>">
                        <a href="<?php echo $downloadLink; ?>"><i class="icon-download"></i></a>
                    </td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
		<?php
	}
}
