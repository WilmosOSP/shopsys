<?php

namespace Shopsys\FrameworkBundle\Model\Pricing\Group;

use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Setting\Setting;

class PricingGroupSettingFacade
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupRepository
     */
    protected $pricingGroupRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade
     */
    protected $adminDomainTabsFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Setting\Setting
     */
    protected $setting;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupRepository $pricingGroupRepository
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade
     * @param \Shopsys\FrameworkBundle\Component\Setting\Setting $setting
     */
    public function __construct(
        PricingGroupRepository $pricingGroupRepository,
        Domain $domain,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        Setting $setting
    ) {
        $this->pricingGroupRepository = $pricingGroupRepository;
        $this->domain = $domain;
        $this->adminDomainTabsFacade = $adminDomainTabsFacade;
        $this->setting = $setting;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     * @return bool
     */
    public function isPricingGroupUsedOnSelectedDomain(PricingGroup $pricingGroup)
    {
        return $this->pricingGroupRepository->existsCustomerUserWithPricingGroup($pricingGroup)
            || $this->isPricingGroupDefaultOnSelectedDomain($pricingGroup);
    }

    /**
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup
     */
    public function getDefaultPricingGroupByDomainId($domainId)
    {
        $defaultPricingGroupId = $this->setting->getForDomain(Setting::DEFAULT_PRICING_GROUP, $domainId);

        return $this->pricingGroupRepository->getById($defaultPricingGroupId);
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup
     */
    public function getDefaultPricingGroupByCurrentDomain()
    {
        return $this->getDefaultPricingGroupByDomainId($this->domain->getId());
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup
     */
    public function getDefaultPricingGroupBySelectedDomain()
    {
        return $this->getDefaultPricingGroupByDomainId($this->adminDomainTabsFacade->getSelectedDomainId());
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     */
    public function setDefaultPricingGroupForSelectedDomain(PricingGroup $pricingGroup)
    {
        $this->setting->setForDomain(Setting::DEFAULT_PRICING_GROUP, $pricingGroup->getId(), $this->adminDomainTabsFacade->getSelectedDomainId());
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     * @return bool
     */
    public function isPricingGroupDefaultOnSelectedDomain(PricingGroup $pricingGroup)
    {
        return $pricingGroup === $this->getDefaultPricingGroupBySelectedDomain();
    }
}
