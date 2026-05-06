<?php

declare(strict_types = 1);

namespace skinsfix;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Skin;
use pocketmine\utils\SingletonTrait;
use pocketmine\network\mcpe\convert\TypeConverter;
use Ramsey\Uuid\Uuid;
use function chr;
use function imagecolorat;
use function imagecreatefrompng;
use function imagesx;
use function imagesy;

final class SkinsFix extends PluginBase{

    use SingletonTrait;

    protected function onLoad() : void{
        self::setInstance($this);

        TypeConverter::getInstance()->setSkinAdapter(new SkinAdapterPersona());
    }

    public function getSteveSkin(string $capeData) : Skin{
        $image = imagecreatefrompng($this->getResourceFolder(). "steve.png");

        $skinData = "";
        for($y = 0; $y < imagesy($image); $y++){
            for($x = 0; $x < imagesx($image); $x++){
                $rgba = imagecolorat($image, $x, $y);
                $a = ((~($rgba >> 24)) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $skinData .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }

        return new Skin(Uuid::uuid4()->toString(), $skinData, $capeData, "geometry.humanoid.custom");
    }
}