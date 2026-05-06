<?php

declare(strict_types = 1);

namespace skinsfix;

use pocketmine\network\mcpe\convert\LegacySkinAdapter;
use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use pocketmine\entity\Skin;
use pocketmine\entity\InvalidSkinException;
use function explode;
use function is_array;
use function is_string;
use function json_decode;

final class SkinAdapterPersona extends LegacySkinAdapter{

    public function fromSkinData(SkinData $data) : Skin{
        $capeData = $data->getCapeImage()->getData();
        $geometryName = explode(".", json_decode($data->getResourcePatch(), true)["geometry"]["default"]);
        if(!isset($geometryName[2])){
            return SkinsFix::getInstance()->getSteveSkin($capeData);
        }

        $patch = json_decode($data->getResourcePatch(), true);
        if(is_array($patch) && isset($patch["geometry"]["default"]) && is_string($patch["geometry"]["default"])){
            $geometryName = $patch["geometry"]["default"];
        } else {
            throw new InvalidSkinException("Missing geometry name field");
        }

        return new Skin($data->getSkinId(), $data->getSkinImage()->getData(), $capeData, $geometryName, $data->getGeometryData());
    }
}