## How to make app working

- Clone the repository using `git clone https://github.com/OmarAshour207/metricalo.git`.
- Run `cd /metricalo` then `composer install`.
- Run `symfony serve`
- Congrats it works.

# There are 2 ways to execute payment purchase via API or CLI.

# via API.

## endpoint GET: /{aci|shift4}

We should enter many params to the URL 
- amount: `92.00`
- currency: `EUR, USD`
- card_number: `4200000000000000`
- card_exp_year: `2035`
- card_exp_month: `05`
- card_cvv: `132`

# via CLI
- run command `php bin/console payment:purchase shift4a --amount=100.00 --currency=EUR --card_number=4111111111111111 --card_exp_year=2025 --card_exp_month=12 --card_cvv=123`

## Api collection link
[Metricalo Collection](https://api.postman.com/collections/8536121-c956b8d8-f43c-486b-bb4d-98f1da0dc36e?access_key=PMAT-01JNCCPT2HW4V2Z7Q9T5DZWE60)
