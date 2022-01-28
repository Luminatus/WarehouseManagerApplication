<?php

namespace Lumie\WarehouseManagerApplication\Entity\Product;

use Lumie\WarehouseManagerApplication\Structure\Enum\KeyboardLayout;

class Keyboard extends AbstractProduct
{
    protected KeyboardLayout $layout;

    protected bool $isRGB;

    /**
     * Get the value of isRGB
     *
     * @return bool
     */
    public function getIsRGB(): bool
    {
        return $this->isRGB;
    }

    /**
     * Set the value of isRGB
     *
     * @param bool $isRGB
     *
     * @return self
     */
    public function setIsRGB(bool $isRGB): self
    {
        $this->isRGB = $isRGB;

        return $this;
    }

    /**
     * Get the value of layout
     *
     * @return KeyboardLayout
     */
    public function getLayout(): KeyboardLayout
    {
        return $this->layout;
    }

    /**
     * Set the value of layout
     *
     * @param KeyboardLayout $layout
     *
     * @return self
     */
    public function setLayout(KeyboardLayout $layout): self
    {
        $this->layout = $layout;

        return $this;
    }
}
