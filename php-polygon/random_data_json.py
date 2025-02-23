import random
import json

def generate_random_stock_data(ticker):
    # Generate random stock data
    start_open = round(random.uniform(1, 200), 7)
    start_close = round(random.uniform(1, 200), 7)
    start_high = round(max(start_open, start_close, random.uniform(1, 200)), 7)
    start_low = round(min(start_open, start_close, random.uniform(1, 200)), 7)
    start_volume = random.randint(1000, 20000000)
    end_open = round(random.uniform(1, 200), 7)
    end_close = round(random.uniform(1, 200), 7)
    end_high = round(max(end_open, end_close, random.uniform(1, 200)), 7)
    end_low = round(min(end_open, end_close, random.uniform(1, 200)), 7)
    end_volume = random.randint(1000, 10000000)

    stock_data = {
        "ticker": ticker,
        "start_date": "2021-11-10",
        "end_date": "2023-10-25",
        "start_open": f"{start_open}",
        "start_close": f"{start_close}",
        "start_high": f"{start_high}",
        "start_low": f"{start_low}",
        "start_volume": start_volume,
        "start_volume_weighted_price": f"{start_open}",
        "end_open": f"{end_open}",
        "end_close": f"{end_close}",
        "end_high": f"{end_high}",
        "end_low": f"{end_low}",
        "end_volume": end_volume,
        "end_volume_weighted_price": f"{end_open}"
    }
    return stock_data

# Existing data
existing_data = [{"ticker":"A","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"159.0000000","start_close":"159.0000000","start_high":"159.0000000","start_low":"156.0000000","start_volume":1093196,"start_volume_weighted_price":"159.0000000","end_open":"102.0000000","end_close":"103.0000000","end_high":"104.0000000","end_low":"100.0000000","end_volume":2699609,"end_volume_weighted_price":"102.0000000"},{"ticker":"AA","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"47.0000000","start_close":"46.0000000","start_high":"47.0000000","start_low":"45.0000000","start_volume":5964720,"start_volume_weighted_price":"46.0000000","end_open":"23.0000000","end_close":"23.0000000","end_high":"24.0000000","end_low":"23.0000000","end_volume":5510187,"end_volume_weighted_price":"23.0000000"},{"ticker":"AAA","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"25.0000000","start_close":"25.0000000","start_high":"25.0000000","start_low":"25.0000000","start_volume":2905,"start_volume_weighted_price":"25.0000000","end_open":"24.0000000","end_close":"24.0000000","end_high":"24.0000000","end_low":"24.0000000","end_volume":2399,"end_volume_weighted_price":"24.0000000"},{"ticker":"AAAU","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"18.0000000","start_close":"18.0000000","start_high":"18.0000000","start_low":"18.0000000","start_volume":399818,"start_volume_weighted_price":"18.0000000","end_open":"19.0000000","end_close":"19.0000000","end_high":"19.0000000","end_low":"19.0000000","end_volume":1700320,"end_volume_weighted_price":"19.0000000"},{"ticker":"AAC","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"9.0000000","start_close":"9.0000000","start_high":"9.0000000","start_low":"9.0000000","start_volume":432475,"start_volume_weighted_price":"9.0000000","end_open":"10.0000000","end_close":"10.0000000","end_high":"10.0000000","end_low":"10.0000000","end_volume":71407,"end_volume_weighted_price":"10.0000000"},{"ticker":"AAC.U","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"10.0000000","start_close":"10.0000000","start_high":"10.0000000","start_low":"10.0000000","start_volume":67229,"start_volume_weighted_price":"10.0000000","end_open":"10.0000000","end_close":"10.0000000","end_high":"10.0000000","end_low":"10.0000000","end_volume":9853,"end_volume_weighted_price":"10.0000000"},{"ticker":"AAC.WS","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"1.0000000","start_close":"1.0000000","start_high":"1.0000000","start_low":"1.0000000","start_volume":152778,"start_volume_weighted_price":"1.0000000","end_open":"0.0000000","end_close":"0.0000000","end_high":"0.0000000","end_low":"0.0000000","end_volume":22637,"end_volume_weighted_price":"0.0000000"},{"ticker":"AACG","start_date":"2021-11-10","end_date":"2023-10-25","start_open":"2.0000000","start_close":"2.0000000","start_high":"2.0000000","start_low":"2.0000000","start_volume":24238,"start_volume_weighted_price":"2.0000000","end_open":"1.0000000","end_close":"1.0000000","end_high":"1.0000000","end_low":"1.0000000","end_volume":2208,"end_volume_weighted_price":"1.0000000"}]

# Generate data for 10,000 additional stocks
for i in range(10000):
    ticker = f"B{i:05d}"
    new_stock_data = generate_random_stock_data(ticker)
    existing_data.append(new_stock_data)

# Convert to JSON
json_data = json.dumps(existing_data, indent=4)

# Write to a file
with open('stock_data.json', 'w') as file:
    file.write(json_data)

print("Data saved to stock_data.json")
