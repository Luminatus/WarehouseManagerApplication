<?php

namespace Lumie\WarehouseManagerApplication\Entity\Product;

use Lumie\WarehouseManagerApplication\Structure\Enum\HeadphoneAudioType;
use Lumie\WarehouseManagerApplication\Structure\Enum\HeadphoneConnectionType;

class Headphone extends AbstractProduct
{
    protected HeadphoneAudioType $audioType;

    protected HeadphoneConnectionType $connectionType;

    /**
     * Get the value of audioType
     *
     * @return HeadphoneAudioType
     */
    public function getAudioType(): HeadphoneAudioType
    {
        return $this->audioType;
    }

    /**
     * Set the value of audioType
     *
     * @param HeadphoneAudioType $audioType
     *
     * @return self
     */
    public function setAudioType(HeadphoneAudioType $audioType): self
    {
        $this->audioType = $audioType;

        return $this;
    }

    /**
     * Get the value of connectionType
     *
     * @return HeadphoneConnectionType
     */
    public function getConnectionType(): HeadphoneConnectionType
    {
        return $this->connectionType;
    }

    /**
     * Set the value of connectionType
     *
     * @param HeadphoneConnectionType $connectionType
     *
     * @return self
     */
    public function setConnectionType(HeadphoneConnectionType $connectionType): self
    {
        $this->connectionType = $connectionType;

        return $this;
    }
}
