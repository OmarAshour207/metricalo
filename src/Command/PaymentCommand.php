<?php

namespace App\Command;

use App\Services\PaymentService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'payment:purchase',
    description: 'Process purchase using a specific payment provider.',
)]
class PaymentCommand extends Command
{

    public function __construct(private readonly PaymentService $paymentService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('provider', InputArgument::REQUIRED, 'Payment provider (aci|shift4)')
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'The purchase amount')
            ->addOption('currency', null, InputOption::VALUE_REQUIRED, 'The currency')
            ->addOption('card_number', null, InputOption::VALUE_REQUIRED, 'Card number')
            ->addOption('card_exp_year', null, InputOption::VALUE_REQUIRED, 'Card expiration year')
            ->addOption('card_exp_month', null, InputOption::VALUE_REQUIRED, 'Card expiration month')
            ->addOption('card_cvv', null, InputOption::VALUE_REQUIRED, 'Card CVV');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $provider = $input->getArgument('provider');

        $params = [
            'amount' => $input->getOption('amount'),
            'currency' => $input->getOption('currency'),
            'card_number' => $input->getOption('card_number'),
            'card_exp_year' => $input->getOption('card_exp_year'),
            'card_exp_month' => $input->getOption('card_exp_month'),
            'card_cvv' => $input->getOption('card_cvv'),
        ];

        try {
            $response = $this->paymentService->charge($provider, $params);
            $output->writeln('<info>Payment processed successfully!</info>');
            $output->writeln(print_r($response, true));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
