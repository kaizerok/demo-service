<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Entity;
use App\Models\PaymentSystem;
use App\Models\ProjectEntityRule;

/**
 * Class EntityService
 * Define default entities
 *
 * @package App\Services
 */
class EntityRolesService
{
    /**
     * @var Entity
     */
    private $processingEntity;

    /**
     * @var Entity
     */
    private $masterEntity;

    /**
     * @var Entity
     */
    private $taxEntity;

    /**
     * @var bool
     */
    private $isTaxPayable = false;

    /**
     * EntityRolesService constructor.
     *
     * @param PaymentSystem $paymentSystem
     * @param Country       $userCountry
     */
    public function __construct(PaymentSystem $paymentSystem, Country $userCountry)
    {
        $this->masterEntity = Entity::getMaster();
        $this->processingEntity = Entity::getDefaultProcessing();
        $this->defineEntityRoles($paymentSystem, $userCountry);
    }

    /**
     * @param PaymentSystem $paymentSystem
     * @param Country       $userCountry
     */
    protected function defineEntityRoles(PaymentSystem $paymentSystem, Country $userCountry)
    {
        $projectEntityRules = ProjectEntityRule::getListByPaymentSystemId($paymentSystem->getId());

        if ($projectEntityRules->isEmpty()) {
            $this->taxEntity = $this->masterEntity;
        } else {
            $defaultByCountry = ProjectEntityRule::ALL_COUNTRIES_ID;
            /** @var ProjectEntityRule $projectEntityRule */
            $projectEntityRule = $projectEntityRules->where('country_id', $userCountry->getId())->first()
                ?: $projectEntityRules->where('country_id', $defaultByCountry)->first();

            $this->processingEntity = Entity::findById($projectEntityRule->getProcessingEntityId());
            $this->taxEntity = Entity::findById($projectEntityRule->getTaxEntityId());

            $this->isTaxPayable = $this->taxEntity->isTaxPayable($userCountry->getId());
        }
    }

    /**
     * @return Entity
     */
    public function getProcessingEntity()
    {
        return $this->processingEntity;
    }

    /**
     * @return Entity
     */
    public function getMasterEntity()
    {
        return $this->masterEntity;
    }

    /**
     * @return Entity
     */
    public function getTaxEntity()
    {
        return $this->taxEntity;
    }

    /**
     * @return bool
     */
    public function isTaxPayable()
    {
        return $this->isTaxPayable;
    }
}
