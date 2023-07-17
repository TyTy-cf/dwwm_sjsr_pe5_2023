<?php

namespace App\Service\Entity;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Service\TextService;

class CountryService
{

    public function __construct(
        private TextService $textService,
        private CountryRepository $countryRepository
    ) { }

    /**
     * @param Country $country
     */
    public function initEmptyFields(Country $country): void {
        $country->setSlug(
            $this->textService->slugify($country->getNationality())
        );
        $country->setUrlFlag('https://flagcdn.com/32x24/'.$country->getCode().'.png');
        $this->countryRepository->save($country, true);
    }

}
