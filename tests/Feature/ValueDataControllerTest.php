<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ValueData;

class ValueDataControllerTest extends TestCase
{
  use RefreshDatabase;

  // Test storing key value
  public function test_store_key_value()
  {
    $response = $this->postJson('/api/object', [
      'key' => 'mykey',
      'value' => 'value1',
    ]);

    $response->assertStatus(201)->assertJson([
      'message' => 'Value stored successfully!',
    ]);

    $this->assertDatabaseHas('values_data', [
      'key' => 'mykey',
      'value' => json_encode('value1'), // Ensure the value is correctly stored as JSON
    ]);
  }

  // Test storing empty key
  public function test_store_fails_with_missing_key()
  {
    // missing Key
    $response = $this->postJson('/api/object', [
      'value' => 'value1',
    ]);

    $response->assertStatus(400)->assertJson([
      'message' => 'Validation Error',
      'errors' => [
        'key' => ['The key field is required.'],
      ],
    ]);
  }

  // Test storing empty value
  public function test_store_fails_with_missing_value()
  {
    // missing value
    $response = $this->postJson('/api/object', [
      'key' => 'mykey',
    ]);

    $response->assertStatus(400)->assertJson([
      'message' => 'Validation Error',
      'errors' => [
        'value' => ['The value field is required.'],
      ],
    ]);
  }

  // Test storing empty data
  public function test_store_fails_with_empty_data()
  {
    // empty data
    $response = $this->postJson('/api/object', []);

    $response->assertStatus(400)->assertJson([
      'message' => 'Validation Error',
      'errors' => [
        'key' => ['The key field is required.'],
        'value' => ['The value field is required.'],
      ],
    ]);
  }

  // Test get all records
  public function test_get_all_records()
  {
    ValueData::create(['key' => 'mykey', 'value' => 'value1', 'created_at' => now()]);
    ValueData::create(['key' => 'mykey2', 'value' => 'value2', 'created_at' => now()]);

    $response = $this->getJson('/api/object/get_all_records');

    $response->assertStatus(200)->assertJsonCount(2); // make sure got exact 2 records 
  }

  // Test get data by key
  public function test_get_by_key_success_without_timestamp()
  {
    $valueData = ValueData::factory()->create(['key' => 'myKey', 'value' => 'value1']); // Create test data

    $response = $this->getJson('/api/object/myKey');

    $response->assertStatus(200)->assertJson([
      'key' => 'myKey',
      'value' => $valueData->value,
    ]);
  }

  // Test get data by key and timestamp
  public function test_get_by_key_success_with_timestamp()
  {
    $timestamp = now()->timestamp;
    $valueData = ValueData::factory()->create([
      'key' => 'myKey',
      'value' => 'value1',
      'created_at' => date('Y-m-d H:i:s', $timestamp),
    ]);

    $response = $this->getJson('/api/object/myKey?timestamp=' . $timestamp);

    $response->assertStatus(200)->assertJson([
      'key' => 'myKey',
      'value' => $valueData->value,
    ]);
  }

  // Test get data without timestamp return latest record
  public function test_get_by_key_returns_latest_record()
  {
    $minus1DayTimestamp = now()->subDay()->timestamp;
    $currentTimestamp = now()->timestamp;
    ValueData::factory()->create(['key' => 'myKey', 'created_at' => $minus1DayTimestamp]);
    $latestValueData = ValueData::factory()->create(['key' => 'myKey', 'created_at' => $currentTimestamp]);

    $response = $this->getJson('/api/object/myKey');

    $response->assertStatus(200)->assertJson([
      'key' => 'myKey',
      'value' => $latestValueData->value,
    ]);
  }

  // Test get data with invalid key
  public function test_get_by_key_not_found()
  {
    $response = $this->getJson('/api/object/invalidKey');

    $response->assertStatus(404)->assertJson([
      'error' => 'Key not found',
    ]);
  }

  // Test get data with invalid timestamp
  public function test_get_by_key_invalid_timestamp()
  {
    $response = $this->getJson('/api/object/myKey?timestamp=invalidTimestamp');

    $response->assertStatus(400)->assertJson([
      'error' => 'Timestamp must be numeric',
    ]);
  }

  // Test get data with correct key but incorrect timestamp
  public function test_get_by_key_no_data_for_timestamp()
  {
    $timestamp = now()->timestamp;
    $valueData = ValueData::factory()->create([
      'key' => 'testKey',
      'created_at' => $timestamp,
    ]);

    $minus1DayTimestamp = now()->subDay()->timestamp; // minus 1 day timestamp
    $response = $this->getJson('/api/object/testKey?timestamp=' . $minus1DayTimestamp);

    $response->assertStatus(404)->assertJson([
      'error' => 'Key not found',
    ]);
  }

  // Test get data with no data in database
  public function test_get_by_key_with_empty_database()
  {
    $response = $this->getJson('/api/object/myKey');

    $response->assertStatus(404)->assertJson([
      'error' => 'Key not found',
    ]);
  }

  // Test invalid API route
  public function test_invalid_route()
  {
    $response = $this->getJson('/api/invalidRoute');

    $response->assertStatus(404)->assertJson([
      'error' => 'API route not found',
    ]);
  }

  // Test not allowed method API route
  public function test_invalid_method_route()
  {
    $response = $this->getJson('/api/object');

    $response->assertStatus(405)->assertJson([
      'error' => 'Method Not Allowed',
    ]);
  }
}
