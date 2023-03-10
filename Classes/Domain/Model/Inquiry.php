<?php
namespace WebsiteMensch\Sitepackage\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
/***
 *
 * This file is part of the "Sitepackage" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Andre Kraus <info@website-mensch.de>, Website Mensch
 *
 ***/
/**
 * Inquiry
 */
class Inquiry extends AbstractEntity
{

    /**
     * formtitle
     *
     * @var string
     */
    protected $formtitle = '';

    /**
     * fullname
     *
     * @var string
     */
    protected $fullname = '';

    /**
     * emailTo
     *
     * @var string
     */
    protected $emailTo = '';

    /**
     * emailFrom
     *
     * @var string
     */
    protected $emailFrom = '';

    /**
     * content
     *
     * @var string
     */
    protected $content = '';

    /**
     * privacy
     *
     * @var string
     */
    protected $privacy = '';



    /**
     * Get formtitle
     *
     * @return  string
     */
    public function getFormtitle()
    {
        return $this->formtitle;
    }

    /**
     * Set formtitle
     *
     * @param  string  $formtitle  formtitle
     *
     * @return  void
     */
    public function setFormtitle(string $formtitle)
    {
        $this->formtitle = $formtitle;


    }

    /**
     * Get fullname
     *
     * @return  string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set fullname
     *
     * @param  string  $fullname  fullname
     *
     * @return  void
     */
    public function setFullname(string $fullname)
    {
        $this->fullname = $fullname;


    }

    /**
     * Get emailTo
     *
     * @return  string
     */
    public function getEmailTo()
    {
        return $this->emailTo;
    }

    /**
     * Set emailTo
     *
     * @param  string  $emailTo  emailTo
     *
     * @return  void
     */
    public function setEmailTo(string $emailTo)
    {
        $this->emailTo = $emailTo;


    }

    /**
     * Get emailFrom
     *
     * @return  string
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * Set emailFrom
     *
     * @param  string  $emailFrom  emailFrom
     *
     * @return  void
     */
    public function setEmailFrom(string $emailFrom)
    {
        $this->emailFrom = $emailFrom;


    }

    /**
     * Get content
     *
     * @return  string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param  string  $content  content
     *
     * @return  void
     */
    public function setContent(string $content)
    {
        $this->content = $content;


    }

    /**
     * Get privacy
     *
     * @return  string
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set privacy
     *
     * @param  string  $privacy  privacy
     *
     * @return  void
     */
    public function setPrivacy(string $privacy)
    {
        $this->privacy = $privacy;


    }
}
