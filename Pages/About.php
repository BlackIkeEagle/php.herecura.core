<?php
/**
 * Pages_About.
 *
 * @uses Page_Abstract
 * @author Ike Devolder <ike DOT devolder AT gmail DOT com>
 */
class Pages_About extends APages{
	/**
	 * {@inheritdoc}
	 */
	public function index() {
		if($this->pageType == IPages::JSON) {
			$this->document->site = Config::siteInfo()->site;
			$this->document->version = Config::siteInfo()->version;
			$this->document->version_ext = Config::siteInfo()->versionExtension;
			$this->document->creator = Config::siteInfo()->creator;
			$this->document->contributors = Config::siteInfo()->contributors;
		} elseif($this->pageType == IPages::HTML) {
			$this->document->setTitleAppend('About');
			$content = $this->document->getElementById('content');
			$appInfo = $this->document->createElement('table');
			$appInfo->setAttribute('class', 'about');

			$toolname = $this->document->createElement('tr');
			$toolname->appendChild($this->document->createElement('td', 'site:'));
			$toolname->appendChild($this->document->createElement('td', Config::siteInfo()->site));

			$version = $this->document->createElement('tr');
			$version->appendChild($this->document->createElement('td', 'version:'));
			$version->appendChild($this->document->createElement('td', Config::siteInfo()->version.(Config::siteInfo()->versionExtension !== '' ? ' ('.Config::siteInfo()->versionExtension.')' : '')));

			$creator = $this->document->createElement('tr');
			$creator->appendChild($this->document->createElement('td', 'creator:'));
			$creator->appendChild($this->document->createElement('td', Config::siteInfo()->creator));

			$contributors = $this->document->createElement('tr');
			$contributors->appendChild($this->document->createElement('td', 'contributors:'));
			$contributorList = $this->document->createElement('td');
			foreach(Config::siteInfo()->contributors as $contributor) {
				$contributorList->appendChild($this->document->createTextNode($contributor));
				$contributorList->appendChild($this->document->createElement('br'));
			}
			$contributors->appendChild($contributorList);

			$appInfo->appendChild($toolname);
			$appInfo->appendChild($version);
			$appInfo->appendChild($creator);
			$appInfo->appendChild($contributors);

			$content->appendChild($appInfo);
		}
	}
}
