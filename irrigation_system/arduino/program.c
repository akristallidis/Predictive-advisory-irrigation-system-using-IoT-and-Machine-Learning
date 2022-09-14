#include <DHT.h>
#include <SPI.h>
#include <Ethernet.h>

#define DHTTYPE DHT11
#define DHTPIN 4

DHT dht(DHTPIN, DHTTYPE);

int crop_id = 1;
float photoResistorValue; //Variable for photo resistor value
float percentagePhotoResistorValue; //Variable for conversion photo resistor value to percentage value
float humidityValue; //Variable for humidity
float temperatureValue; //Variable for temperature
float soilMoistureValue; //Variable for soil moisture value
float percentageSoilMoisture; //Variable for conversion soil moisture value to percentage value
byte sensorInterrupt = 0;
byte flowPin = 2;
float calibrationFactor = 4.5;
volatile byte pulseCount;
float flowRate;
float flowMilliLiters;
float totalMilliLiters;
float totalLiters;
unsigned long oldTime;
long runningTime;
long measurementTime;

// For ethernet shield 2
byte mac[] = { 0xA8, 0x61, 0x0A, 0xAE, 0x89, 0xDD }; //Ehternet shield 2 MAC address
EthernetClient client;

// For connection at server
char server[] = "ubisys.gr"; //Name address for webserver
int    HTTP_PORT   = 80; //Server port
String HTTP_METHOD = "GET"; //Request method. Can also be POST
char   HOST_NAME[] = "ubisys.gr"; //Host name
String PATH_NAME   = "/smartfarm/backend/API/data_table/newDataRow.php"; //Pathn name
String data; //String for sending data to server

float percentageLowValue = 0; //Variable for low limit
float percentageHighValue = 100; //Variable for hig limit

void setup() {
  
  Serial.begin(9600);
  
  dht.begin();
  pinMode (flowPin, INPUT);
  pulseCount = 0;
  flowRate = 0.0;
  flowMilliLiters = 0;
  totalMilliLiters = 0;
  oldTime = 0;
  attachInterrupt (sensorInterrupt, pulseCounter, FALLING);
  
  Serial.println("Obtaining an IP address using DHCP");
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to obtaining an IP address");

    // check for Ethernet hardware present
    if (Ethernet.hardwareStatus() == EthernetNoHardware)
      Serial.println("Ethernet shield was not found");

    // check for Ethernet cable
    if (Ethernet.linkStatus() == LinkOFF)
      Serial.println("Ethernet cable is not connected.");

    while (true);
  }
  // print out Arduino's IP address, subnet mask, gateway's IP address, and DNS server's IP address
  Serial.print("- Arduino's IP address   : ");
  Serial.println(Ethernet.localIP());

  Serial.print("- Gateway's IP address   : ");
  Serial.println(Ethernet.gatewayIP());

  Serial.print("- Network's subnet mask  : ");
  Serial.println(Ethernet.subnetMask());

  Serial.print("- DNS server's IP address: ");
  Serial.println(Ethernet.dnsServerIP());
  Serial.println();
  
}

void loop() {
  
  photoResistorValue = analogRead(A0);
  percentagePhotoResistorValue = map(photoResistorValue, 0, 1000, percentageLowValue, percentageHighValue);
  measurementTime = millis() + 10000;
  if(percentagePhotoResistorValue > 20) {
    humidityValue = dht.readHumidity();
    temperatureValue = dht.readTemperature();
    soilMoistureValue = analogRead(A5);
    percentageSoilMoisture = map(soilMoistureValue, 1023, 0, percentageLowValue, percentageHighValue);
    while (1) {
      if((millis() - oldTime) > 1000) {
        detachInterrupt(sensorInterrupt);
        flowRate = ((1000.0 / (millis() - oldTime)) * pulseCount) / calibrationFactor;
        oldTime = millis();
        flowMilliLiters = (flowRate / 60) * 1000;
        totalMilliLiters += flowMilliLiters;
        totalLiters = totalMilliLiters;
        pulseCount = 0;
        attachInterrupt(sensorInterrupt, pulseCounter, FALLING);
      }
      if (flowRate == 0) break;
    }
    data = String("?crop_id=") + crop_id + String("&soilMoisture=") + percentageSoilMoisture + String("&Photoresistor=") + percentagePhotoResistorValue + String("&humidity=") + humidityValue + String("&temperature=") + temperatureValue + String("&liters=") + totalLiters;
    Serial.println(data);
    if (client.connect(server, HTTP_PORT)) {
      Serial.print("connected to ");
      Serial.println(client.remoteIP());
      client.println(HTTP_METHOD + " " + PATH_NAME + data + " HTTP/1.1");
      client.println("Host: " + String(HOST_NAME));
      client.println("Content-Type: application/x-www-form-urlencoded");
      client.println("Connection: close");
      client.println(); // end HTTP header
    }
    else {
      // if you didn't get a connection to the server:
      Serial.println("connection failed");
    }
    Serial.print("String directly from WebServer is: ");
    while(client.connected()) {
      if(client.available()){
      // read an incoming byte from the server and print it to serial monitor:
      char c = client.read();
      Serial.print(c);
      }
    }
    totalMilliLiters = 0;
    totalLiters = 0;
    while (millis( )< measurementTime && percentagePhotoResistorValue > 20) {
      photoResistorValue = analogRead(A0);
      percentagePhotoResistorValue = map(photoResistorValue, 0, 1000, percentageLowValue, percentageHighValue);
      humidityValue = dht.readHumidity();
      temperatureValue = dht.readTemperature();
      soilMoistureValue = analogRead(A5);
      percentageSoilMoisture = map(soilMoistureValue, 1023, 0, percentageLowValue, percentageHighValue);
      while (1) {
        if((millis() - oldTime) > 1000) {
          detachInterrupt(sensorInterrupt);
          flowRate = ((1000.0 / (millis() - oldTime)) * pulseCount) / calibrationFactor;
          oldTime = millis();
          flowMilliLiters = (flowRate / 60) * 1000;
          totalMilliLiters += flowMilliLiters;
          totalLiters = totalMilliLiters;
          pulseCount = 0;
          attachInterrupt(sensorInterrupt, pulseCounter, FALLING);
        }
        if (flowRate == 0) break;
      }
      if (totalLiters > 0) {
        measurementTime = millis() + 10000;
        data = String("?crop_id=") + crop_id + String("&soilMoisture=") + percentageSoilMoisture + String("&Photoresistor=") + percentagePhotoResistorValue + String("&humidity=") + humidityValue + String("&temperature=") + temperatureValue + String("&liters=") + totalLiters;
        Serial.println(data);
        if (client.connect(server, HTTP_PORT)) {
          Serial.print("connected to ");
          Serial.println(client.remoteIP());
          client.println(HTTP_METHOD + " " + PATH_NAME + data + " HTTP/1.1");
          client.println("Host: " + String(HOST_NAME));
          client.println("Content-Type: application/x-www-form-urlencoded");
          client.println("Connection: close");
          client.println(); // end HTTP header
        }
        else {
          // if you didn't get a connection to the server:
          Serial.println("connection failed");
        }
        Serial.print("String directly from WebServer is: ");
        while(client.connected()) {
          if(client.available()) {
          // read an incoming byte from the server and print it to serial monitor:
          char c = client.read();
          Serial.print(c);
          }
        }
      }
      totalMilliLiters = 0;
      totalLiters = 0;
      delay(1000);
    }
  }
  else {
    delay(1000); //If it is night take photoResistorValue every to 1 min to check
  }
}

void pulseCounter() {
  pulseCount ++;
}