<?php

namespace Lumie\WarehouseManagerApplication\Command;

use Lumie\WarehouseManagerApplication\Service\TestService;
use Lumie\WarehouseManagerApplication\Service\WarehouseManagerService;
use Lumie\WarehouseManagerApplication\Structure\DTO\Warehouse;
use Lumie\WarehouseManagerApplication\Structure\DTO\WarehouseStockInfo;
use Lumie\WarehouseManagerApplication\Structure\Entity\WarehouseStock;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class WarehouseManagerCommand extends Command
{
    const MENU_HEADER = 'Choose an action';

    const MENU_OPTION_CREATE_WAREHOUSE = 1;
    const MENU_STRING_CREATE_WAREHOUSE = 'Create warehouse';
    const MENU_OPTION_STOCK_INFO = 2;
    const MENU_STRING_STOCK_INFO = 'Get warehouse stock info';
    const MENU_OPTION_MODIFY_STOCK = 3;
    const MENU_STRING_MODIFY_STOCK = 'Modify warehouse stock';

    private static function getMenuOptions()
    {
        return [
            static::MENU_OPTION_CREATE_WAREHOUSE => static::MENU_STRING_CREATE_WAREHOUSE,
            static::MENU_OPTION_STOCK_INFO => static::MENU_STRING_STOCK_INFO,
            static::MENU_OPTION_MODIFY_STOCK => static::MENU_STRING_MODIFY_STOCK
        ];
    }

    private static function getMenuString()
    {
        $string = static::MENU_HEADER;
        foreach (static::getMenuOptions() as $key => $optionString) {
            $string .= "\n\t$key. $optionString";
        }
        $string .= "\n";

        return $string;
    }

    public function __construct(
        protected WarehouseManagerService $warehouseManagerService
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName("app/warehouse-manager");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Hello");
        $this->runMenu($input, $output);
        $output->writeln("Goodbye");

        return 0;
    }

    protected function runMenu(InputInterface $input, OutputInterface $output)
    {
        $running = true;
        while ($running) {
            /** @var QuestionHelper $questionHelper */
            $questionHelper = $this->getHelper('question');

            $answer = $questionHelper->ask($input, $output, new Question(static::getMenuString()));

            switch ($answer) {
                case 1:
                    $this->createWarehouse($input, $output);
                    break;
                case 2:
                    $this->outputStock($input, $output);
                    break;
                case 3:
                    $this->modifyStock($input, $output);
                    break;
                default:
                    $running = false;
            }
        }
    }

    protected function createWarehouse(InputInterface $input, OutputInterface $output)
    {
        $name = $this->askString($input, $output, "Name: ");
        $address = $this->askString($input, $output, "Address: ");
        $capacity = $this->askInt($input, $output, "Capacity: ");

        $warehouseDTO = new Warehouse();
        $warehouseDTO
            ->setName($name)
            ->setAddress($address)
            ->setCapacity($capacity);

        try {
            $this->warehouseManagerService->addWarehouse($warehouseDTO);
            $output->writeln("Warehouse successfully added");
        } catch (\Throwable $th) {
            $output->writeln("An error occured while saving the warehouse: " . $th->getMessage());
        }
    }

    protected function outputStock(InputInterface $input, OutputInterface $output)
    {
        $allStock = $this->warehouseManagerService->getAllStockInfo();

        /** @var WarehouseStockInfo $warehouseStockInfo */
        foreach ($allStock as $warehouseStockInfo) {
            $output->writeln($warehouseStockInfo->getName() . '( id:' . $warehouseStockInfo->getId() . ', capacity: ' . $warehouseStockInfo->getCapacity() . ', free: ' . $warehouseStockInfo->getFreeSpace() . ')');

            /** @var WarehouseStock $warehouseStock */
            foreach ($warehouseStockInfo->getProducts() as $warehouseStock) {
                $output->writeln(sprintf("\t%s %s: %d", $warehouseStock->getProduct()->getBrand()->getName(), $warehouseStock->getProduct()->getName(), $warehouseStock->getQuantity()));
            }
        }
    }

    protected function modifyStock(InputInterface $input, OutputInterface $output)
    {
        $products = $this->warehouseManagerService->getProducts();

        foreach ($products as $product) {
            $output->writeln(sprintf("%s %s (id: %d)", $product->getBrand()->getName(), $product->getName(), $product->getId()));
        }

        $productId = $this->askInt($input, $output, "Id of product: ");
        $warehouseIds = $this->askIntMultiple($input, $output, "Id of warehouse: ");
        $quantity = $this->askInt($input, $output, "Quantity: ");

        try {
            $this->warehouseManagerService->modifyWarehouseProductStock($productId, $quantity, $warehouseIds);
        } catch (\Throwable $th) {
            $output->writeln("Operation failed: " . $th->getMessage());
        }
    }

    private function askInt(InputInterface $input, OutputInterface $output, string $questionString)
    {
        return $this->ask($input, $output, $questionString, 'int');
    }

    private function askIntMultiple(InputInterface $input, OutputInterface $output, string $questionString)
    {
        $answers = [];
        do {
            $answer = $this->ask($input, $output, $questionString, 'int', true);
            if (!empty($answer)) {
                $answers[] = $answer;
            }
        } while (!empty($answer));

        return $answers;
    }

    private function askString(InputInterface $input, OutputInterface $output, string $questionString)
    {
        return $this->ask($input, $output, $questionString, 'string');
    }

    private function ask(InputInterface $input, OutputInterface $output, string $questionString, string $type, bool $allowNull = false)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $question = new Question($questionString);

        switch ($type) {
            case 'int':
                $question->setValidator(function ($answer) use ($allowNull) {
                    if (empty($answer)) {
                        if (!$allowNull) {
                            throw new \RuntimeException("Empty value supplied");
                        }
                    } elseif (!is_numeric($answer)) {
                        throw new \RuntimeException("Invalid value supplied");
                    }

                    return $answer;
                });

                $question->setNormalizer(function ($answer) {
                    return $answer != null ? intval($answer) : $answer;
                });
                break;
            case 'string':
                $question->setValidator(function ($answer) use ($allowNull) {
                    if (empty($answer)) {
                        throw new \RuntimeException("Invalid or empty value supplied");
                    }

                    return $answer;
                });
                break;
        }

        return $questionHelper->ask($input, $output, $question);
    }
}
