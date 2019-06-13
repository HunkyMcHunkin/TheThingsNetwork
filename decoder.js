function Decoder(bytes, port) {
    
  var temp = ((bytes[0] << 8) + bytes[1])/100;
  var ax = bytes[2];
  var ay = bytes[3];
  var az = bytes[4];
  var gx = bytes[5];
  var gy = bytes[6];
  var gz = bytes[7];

  return {
    temperature: temp,
    accX: ax,
    accY: ay,
    accZ: az,
    gyrX: gx,
    gyrY: gy,
    gyrZ: gz
  }
}
