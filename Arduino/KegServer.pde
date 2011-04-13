#include <NewSoftSerial.h>
#include <LiquidCrystal.h> 
#include <Ethernet.h>

#define Apin 2      //White/Blue
#define Bpin 3      //Green
#define Valvepin 7  //Blue 
//#define LEDpin 5 
#define Switchpin 6  //Brown
//5Volts - Orange


LiquidCrystal lcd(14,15,16,17,18,19); 
NewSoftSerial rfidSerial(8,7);
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
byte ip[] = { 192, 168, 1, 11 };
byte server[] = { 97,74,249,144 }; //kegserver
//byte server[] = {74,125,53,100};  //google

Client client(server, 80);

volatile long tapA = 0;
volatile long tapB = 0;

double kegAprice = 0.0;
double kegBprice = 0.0;

void setup() { 
  
  pinMode(Apin, INPUT);      // Make digital 2 an input
  digitalWrite(Apin, HIGH);  // Enable pull up resistor    
  attachInterrupt(0, Aflow, FALLING);
  pinMode(Bpin, INPUT);      // Make digital 3 an input
  digitalWrite(Bpin, HIGH);  // Enable pull up resistor    
  attachInterrupt(1, Bflow, FALLING);  
  pinMode(Valvepin,OUTPUT);
  digitalWrite(Valvepin,LOW);
//  pinMode(LEDpin,OUTPUT);
//  digitalWrite(LEDpin,HIGH);
  pinMode(Switchpin,INPUT);
  digitalWrite(Switchpin,HIGH);  
   
  Ethernet.begin(mac, ip);
  rfidSerial.begin(9600);
  Serial.begin(9600);
  //lcd.begin(16,2);
  //lcd.clear();
    //lcd.print("Connecting...");
}

void loop() {
  lcd.begin(16,2);
  lcd.clear();
  client.flush();
  client.stop();
  if (!client.connect()) {
    delay(1000);
    client.connect();
  }
    //Serial.println("Connected");
  
    Serial.println("Sending Request");
    client.println("GET /psiu/comm.php?kegstatus HTTP/1.1");
    client.println("user-agent: arduino/kegserver");
    client.println("Host: kegserver.com");
    client.println();
  
    unsigned long startTime = millis();
    while ((!client.available()) && ((millis() - startTime ) < 5000)); 
    Serial.println("Getting web data");
   
    char result[64];   
    int bytesread=0;
    while (client.available()){
      char c = client.read();
      Serial.print(c);
      if (bytesread>124) { result[bytesread-125] = c; }
      bytesread++;
    }
    result[bytesread-125] = '\0';
    Serial.println(result); 
    client.flush();
    client.stop();  
    
    lcd.clear();
    
    int numkegs = 2;
    char *c = strtok(result,"\n");

    c = strtok(NULL,"\n");
    //for (int i=0;i<7;i++) { c = strtok(NULL,"\n"); }

    char *d = strtok(c,",");
    free(result);
    if (strcmp("A",d)==0){
      double kegArem = atof(strtok(NULL,","));
      char *kegAName = strtok(NULL,",");
     kegAprice = atof(strtok(NULL," "));
      
      lcd.print((char)127);
      lcd.print(kegAName);
      d = strtok(NULL,",");
    } else { numkegs = 1; }
    if (strcmp(d,"B")==0) {
      double kegBrem = atof(strtok(NULL,","));
      char* kegBName = strtok(NULL,",");
      kegBprice = atof(strtok(NULL," "));
      lcd.setCursor(15-strlen(kegBName),1);
      lcd.print(kegBName);
      lcd.print((char)126);
  
    } else { numkegs = 1; }
    
    
    
//  } else {
//    Serial.println("Couldn't Connect");
//    lcd.clear();
//    lcd.print("Connection Fail");
//  }
  unsigned long time = millis();

  int reset = 0;
  
  while (millis()<(time+60000) && reset==0) {
    if (rfidSerial.available()) 
     {
       if((rfidSerial.read()) == 2) {
         //Serial.println("Scan");
         fobScan();
         reset=1;
       }
     }
    if (digitalRead(Switchpin)==LOW) {
      freeflow();
      reset = 1;
    }
    delay(200);
  }
    delay(100);
}

void freeflow() {
  lcd.clear();
  lcd.print("Valves Open");
  
  tapA=0;
  tapB=0;
  digitalWrite(Valvepin, HIGH);
  bool pin=LOW;

  while (pin==LOW) {
    lcd.setCursor(0,1);
    if(tapA>6100) {
      lcdPrintDouble(tapA/6100.0,3);
      lcd.print("L  ");
    } else {
      lcdPrintDouble(tapA*1000/6100.0,1);
      lcd.print("mL");
    }

    lcd.setCursor(8,1);
    if(tapB>6100) {
      lcdPrintDouble(tapB/6100.0,3);
      lcd.print("L  ");
    } else {
      lcdPrintDouble(tapB*1000/6100.0,1);
      lcd.print("mL");
    }
    pin = digitalRead(Switchpin);
    delay(200);
  }
  
  closeTaps(0);
}

void fobScan() {

  //Serial.println("Reading Fob");
  lcd.clear();
  lcd.print("Reading...");
  
  byte val = 0;
  int bytes = 0;
  
  char code[13];

  while (bytes < 12) {                        // read 10 digit code + 2 digit checksum
    if ( rfidSerial.available() > 0) { 
      val = rfidSerial.read();
      if((val == 0x0D)||(val == 0x0A)||(val == 0x03)||(val == 0x02)) { // if header or stop bytes before the 10 digit reading 
        //Serial.println("Break");
        break;                                    // stop reading
      }
    
      // Do Ascii/Hex conversion:
      if ((val >= '0') && (val <= '9')) {
        val = val - '0';
      } else if ((val >= 'A') && (val <= 'F')) {
        val = 10 + val - 'A';
      }
      code[bytes] = val;
      bytes++;
    }
  }
  long id = 0;
  if (bytes == 12) {                          // if 12 digit read is complete
    for (int i=4; i<10; i++) {
      id = 16*id + code[i];
    }
    //free(code);
    //Serial.println(id);
    //Serial.println("");
    
  } else { return; }
  
  //Serial.println("trying to connect");
  //if (client.connected()) { Serial.println("Already Connected"); }
  //if (!client.connect()) { Serial.println("Can't Connect"); }
  client.connect();
  //Serial.println("connected");
  client.print("GET /psiu/comm.php?fobID=");
  if (id<1000000000) { client.print("0"); }
  if (id<100000000) { client.print("0"); }
  if (id<10000000) { client.print("0"); }
  if (id<1000000) { client.print("0"); }
  client.print(id);
  client.println(" HTTP/1.1");
  client.println("user-agent: arduino/kegserver");
  client.println("Host: kegserver.com");
  client.println();

  //Serial.println("sent data");

  unsigned long startTime = millis();
  while ((!client.available()) && ((millis() - startTime ) < 5000)); 
  //Serial.println("Getting web data");
 
  char result[64];   
  int bytesread=0;
  while (client.available()){
    char c = client.read();
    //Serial.print(c);
    if (bytesread>124) { result[bytesread-125] = c; }
    bytesread++;
  }
  result[bytesread-125] = '\0';
  //Serial.println(result); 
  client.flush();
  client.stop();

  char *c = strtok(result,"\n");
//  free(result);
  c = strtok(NULL,"\n");
  //for (int i=0;i<7;i++) { c = strtok(NULL,"\n"); }
  if (c[0]=='?')
  {
    lcd.clear();
    lcd.print("Unknown FOB");
    lcd.setCursor(0,1);
    if (id<1000000000) { lcd.print("0"); }
    if (id<100000000) { lcd.print("0"); }
    if (id<10000000) { lcd.print("0"); }
    if (id<1000000) { lcd.print("0"); }
    lcd.print(id);
    delay(2000);
    return;
  }  
  double bal = atof(strtok(c,","));
  char *name = strtok(NULL,",");
  name[strlen(name)-1]=' ';
/*
  lcd.clear();
  lcd.print(name);
  lcd.print("($");
  lcdPrintDouble(bal,2);
  lcd.print(")");
*/
  tapA = 0;
  tapB = 0;  
  digitalWrite(Valvepin,HIGH);
  
  unsigned long pourTime = millis();
  int starting=1;
  
  double cost = 0;
  double quantity = 0;
  int quit = 0;
  
  while(bal>cost && quit==0){
    if ((millis() - pourTime ) > 3000) {
      starting = 0;
      if (tapA < 2 && tapB < 2) {
        //closeTaps(id);
        quit=1;
      }
    }
    unsigned long Time2 = millis();
    long tapAcheck = tapA;
    long tapBcheck = tapB;
    while ((millis()-Time2)<1000){

      quantity = tapA*1000/6100.0+tapB*1000/6100.0;
      cost = tapA*kegAprice/6100.0 + tapB*kegBprice/6100.0;

      lcd.clear();
      lcd.print(name);
      lcd.print("($");
      lcdPrintDouble(bal-cost,2);
      lcd.print(")");      
      lcd.setCursor(0,1);      
      lcd.print((int)quantity);
      lcd.print("mL - $");
      lcdPrintDouble(cost,2);
      delay(200);
    }
    if ((tapA-tapAcheck)<2 && (tapB-tapBcheck)<2 && starting==0) {
      //closeTaps(id);
      quit= 1;
    }
  }
  
  free(name);
  free(c);
  free(result);
  
  if(bal<0) {
    bal = 0.00;
    delay(1000);
  }
  closeTaps(id);
  lcd.clear();
  return;
}

void closeTaps(long fob) {
  digitalWrite(Valvepin, LOW);
  
  //lcd.clear();
  
  if (tapA<10 && tapB<10) {
    tapA=0;
    tapB=0;
    return;
  }
  delay(1000);
  //lcd.clear();
  //lcd.print("Sending Data...");
  
  //calculate volume, cost, balance
  //display new balance
  //send data to server
  
  double quantityA = tapA*1000/6100.0;
  double quantityB = tapB*1000/6100.0;
  //double cost = tapA*kegAprice/6100.0 + tapB*kegBprice/6100.0;
 
/*  while (!client.connect()) {
    //lcd.setCursor(0,1);
    //Serial.println("Failed..Retrying");
    delay(2000);
    lcd.clear();
    lcd.print("Sending Data...");
  } 
  
  */
  client.connect();
  delay(100); 
  
  if (tapA>1) {
    tapA=0;
    client.print("GET /psiu/comm.php?transaction&tap=A&fobID=");
    if (fob<1000000000) { client.print("0"); }
    if (fob<100000000) { client.print("0"); }
    if (fob<10000000) { client.print("0"); }
    if (fob<1000000) { client.print("0"); }
    client.print(fob);
    client.print("&quantity=");
    printDouble(quantityA,4);
    client.print("&password=75c6f03161d020201000414cd1501f9f");
    client.println(" HTTP/1.1");
    client.println("user-agent: arduino/kegserver");
    client.println("Host: kegserver.com");
    client.println();
    
    //Serial.println("TAP A");
    
  }

  if (tapB>1) {
    tapB=0;
    client.print("GET /psiu/comm.php?transaction&tap=B&fobID=");
    if (fob<1000000000) { client.print("0"); }
    if (fob<100000000) { client.print("0"); }
    if (fob<10000000) { client.print("0"); }
    if (fob<1000000) { client.print("0"); }
    client.print(fob);
    client.print("&quantity=");
    printDouble(quantityB,4);
    client.print("&password=75c6f03161d020201000414cd1501f9f");
    client.println(" HTTP/1.1");
    client.println("user-agent: arduino/kegserver");
    client.println("Host: kegserver.com");
    client.println();
    
    //Serial.println("TAP B");
    
  }

  unsigned long startTime = millis();
  while ((!client.available()) && ((millis() - startTime ) < 5000)); 
  
  client.flush();
  client.stop();

  return;
}


void lcdPrintDouble( double val, byte precision){
  // prints val on a ver 0012 text lcd with number of decimal places determine by precision
  // precision is a number from 0 to 6 indicating the desired decimial places
  // example: printDouble( 3.1415, 2); // prints 3.14 (two decimal places)

  if(val < 0.0){
    lcd.print('-');
    val = -val;
  }

  lcd.print (int(val));  //prints the int part
  if( precision > 0) {
    lcd.print("."); // print the decimal point
    unsigned long frac;
    unsigned long mult = 1;
    byte padding = precision -1;
    while(precision--)
  mult *=10;

    if(val >= 0)
 frac = (val - int(val)) * mult;
    else
 frac = (int(val)- val ) * mult;
    unsigned long frac1 = frac;
    while( frac1 /= 10 )
 padding--;
    while(  padding--)
 lcd.print("0");
    lcd.print(frac,DEC) ;
  }
}

void printDouble( double val, byte precision){
  // prints val on a ver 0012 text lcd with number of decimal places determine by precision
  // precision is a number from 0 to 6 indicating the desired decimial places
  // example: printDouble( 3.1415, 2); // prints 3.14 (two decimal places)

  if(val < 0.0){
    client.print('-');
    val = -val;
  }

  client.print (int(val));  //prints the int part
  if( precision > 0) {
    client.print("."); // print the decimal point
    unsigned long frac;
    unsigned long mult = 1;
    byte padding = precision -1;
    while(precision--)
  mult *=10;

    if(val >= 0)
 frac = (val - int(val)) * mult;
    else
 frac = (int(val)- val ) * mult;
    unsigned long frac1 = frac;
    while( frac1 /= 10 )
 padding--;
    while(  padding--)
 client.print("0");
    client.print(frac,DEC) ;
  }
}


void Aflow() {
  tapA++;
}

void Bflow() {
  tapB++; 
}
