<?php

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class FormMailCodesGUI
 *
 * @author		Helmut Schottmüller <ilias@aurealis.de>
 */
class FormMailCodesGUI extends ilPropertyFormGUI
{
    protected $guiclass;
    protected $subject;
    protected $sendtype;
    protected $savedmessages;
    protected $mailmessage;
    protected $savemessage;
    protected $savemessagetitle;
    
    public function __construct($guiclass)
    {
        global $DIC;

        parent::__construct();

        $ilAccess = $DIC->access();
        $ilSetting = $DIC->settings();
        $ilUser = $DIC->user();
        $rbacsystem = $DIC->rbac()->system();

        $lng = $this->lng;

        $this->guiclass = $guiclass;
        
        $this->setFormAction($this->ctrl->getFormAction($this->guiclass));
        $this->setTitle($this->lng->txt('compose'));

        $this->subject = new ilTextInputGUI($this->lng->txt('subject'), 'm_subject');
        $this->subject->setSize(50);
        $this->subject->setRequired(true);
        $this->addItem($this->subject);

        $this->sendtype = new ilRadioGroupInputGUI($this->lng->txt('recipients'), "m_notsent");
        $this->sendtype->addOption(new ilCheckboxOption($this->lng->txt("send_to_all"), 0, ''));
        $this->sendtype->addOption(new ilCheckboxOption($this->lng->txt("not_sent_only"), 1, ''));
        $this->sendtype->addOption(new ilCheckboxOption($this->lng->txt("send_to_unanswered"), 3, ''));
        $this->sendtype->addOption(new ilCheckboxOption($this->lng->txt("send_to_answered"), 2, ''));
        $this->addItem($this->sendtype);

        $existingdata = $this->guiclass->getObject()->getExternalCodeRecipients();

        $existingcolumns = array();
        if (count($existingdata)) {
            $first = array_shift($existingdata);
            foreach ($first as $key => $value) {
                if (strcmp($key, 'code') != 0 && strcmp($key, 'email') != 0 && strcmp($key, 'sent') != 0) {
                    array_push($existingcolumns, '[' . $key . ']');
                }
            }
        }

        $settings = $this->guiclass->getObject()->getUserSettings($ilUser->getId(), 'savemessage');
        if (count($settings)) {
            $options = array(0 => $this->lng->txt('please_select'));
            foreach ($settings as $setting) {
                $options[$setting['settings_id']] = $setting['title'];
            }
            $this->savedmessages = new ilSelectInputGUI($this->lng->txt("saved_messages"), "savedmessage");
            $this->savedmessages->setOptions($options);
            $this->addItem($this->savedmessages);
        }

        $this->mailmessage = new ilTextAreaInputGUI($this->lng->txt('message_content'), 'm_message');
        $this->mailmessage->setRequired(true);
        $this->mailmessage->setCols(80);
        $this->mailmessage->setRows(10);
        $this->mailmessage->setInfo(sprintf($this->lng->txt('message_content_info'), join($existingcolumns, ', ')));
        $this->addItem($this->mailmessage);

        // save message
        $this->savemessage = new ilCheckboxInputGUI('', "savemessage");
        $this->savemessage->setOptionTitle($this->lng->txt("save_reuse_message"));
        $this->savemessage->setValue(1);

        $this->savemessagetitle = new ilTextInputGUI($this->lng->txt('save_reuse_title'), 'savemessagetitle');
        $this->savemessagetitle->setSize(60);
        $this->savemessage->addSubItem($this->savemessagetitle);

        $this->addItem($this->savemessage);

        if (count($settings)) {
            if ($ilAccess->checkAccess("write", "", $_GET["ref_id"])) {
                $this->addCommandButton("deleteSavedMessage", $this->lng->txt("delete_saved_message"));
            }
            if ($ilAccess->checkAccess("write", "", $_GET["ref_id"])) {
                $this->addCommandButton("insertSavedMessage", $this->lng->txt("insert_saved_message"));
            }
        }

        if ($ilAccess->checkAccess("write", "", $_GET["ref_id"]) && $rbacsystem->checkAccess('smtp_mail', ilMailGlobalServices::getMailObjectRefId())) {
            if ((int) $ilSetting->get('mail_allow_external')) {
                $this->addCommandButton("sendCodesMail", $this->lng->txt("send"));
            } else {
                ilUtil::sendInfo($lng->txt("cant_send_email_smtp_disabled"));
            }
        } else {
            ilUtil::sendInfo($lng->txt("cannot_send_emails"));
        }
    }
    
    public function getSavedMessages()
    {
        return $this->savedmessages;
    }
    
    public function getMailMessage()
    {
        return $this->mailmessage;
    }
}
