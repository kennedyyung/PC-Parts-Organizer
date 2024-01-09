--
-- 	Database Table Creation
--
--	This file is the sql script that will create the tables 
--  containing all the PC parts for out CPSC 304 project.
--  This will run automatically by the installation script.
--

--  Drop existing tables and ignore errors
drop table CPUCooler_On_Model cascade constraints;
drop table CPUCooler_On cascade constraints;
drop table CaseFan_Inside_Model cascade constraints;
drop table CaseFan_Inside cascade constraints;
drop table CPU_On_Power cascade constraints;
drop table CPU_On cascade constraints;
drop table Motherboard_Powers_Socket cascade constraints;
drop table Motherboard_Powers cascade constraints;
drop table Storage_Requires_RW_Speeds cascade constraints;
drop table Storage_Requires cascade constraints;
drop table Storage_Requires_Price cascade constraints;
drop table GPU_Has_Model cascade constraints;
drop table GPU_Has_Price cascade constraints;
drop table GPU_Has_Power cascade constraints;
drop table GPU_Has cascade constraints;
drop table Monitor_Attached_To_Model cascade constraints;
drop table Monitor_Attached_To cascade constraints;
drop table RAM_Placed_On_Brand cascade constraints;
drop table RAM_Placed_On cascade constraints;
drop table RAM_Placed_On_Power cascade constraints;
drop table PowerSupply_In_Model cascade constraints;
drop table PowerSupply_In cascade constraints;
drop table Case_Contains cascade constraints;
drop table Case_Contains_Brand cascade constraints;
drop table Mouse cascade constraints;
drop table Plugged_Into cascade constraints;
drop table Keyboard cascade constraints;
drop table Switches cascade constraints;
drop table Switches_Price cascade constraints;
drop table Connected_To cascade constraints;


-- Create all the tables
CREATE TABLE CPUCooler_On_Model (
  Model	          VARCHAR(100)	PRIMARY KEY,
  Lighting 	      VARCHAR(100),
  Brand		        VARCHAR(100),
  CPUCooler_Type	VARCHAR(100),
  LCD 		        VARCHAR(100)
);

CREATE TABLE CaseFan_Inside_Model(
  Model	    VARCHAR(100)		PRIMARY KEY,
  Brand		  VARCHAR(100),
  Lighting 	VARCHAR(100)
);

CREATE TABLE CPU_On_Power(
 Cores			VARCHAR(100),
 Generation	INT,
 Power			INT,
 PRIMARY KEY (Cores, Generation)
);

CREATE TABLE Motherboard_Powers_Socket(
  CPU_Model	VARCHAR(100)		PRIMARY KEY,
  Socket 	  VARCHAR(100)
);

CREATE TABLE Storage_Requires_RW_Speeds(
  Model		      VARCHAR(100)		PRIMARY KEY,	
  Read_Speeds	  INT,
  Write_Speeds	INT,
  Brand			    VARCHAR(100)
);

CREATE TABLE Storage_Requires_Price(
  Model		      VARCHAR(100),
  Capacity	    INT,
  Storage_Type  VARCHAR(100),
  Price		      NUMBER,
  PRIMARY KEY (Model, Capacity, Storage_Type)
);

CREATE TABLE RAM_Placed_On_Brand(
  Model   VARCHAR(100)    PRIMARY KEY,
  Brand		VARCHAR(100)
);

CREATE TABLE Case_Contains_Brand(
  Model	  VARCHAR(100)	PRIMARY KEY,
  Brand		VARCHAR(100)
);

CREATE TABLE Mouse(
  Model		        VARCHAR(100),
  Colour		      VARCHAR(100),
  Brand           VARCHAR(100),
  Mouse_Size	    VARCHAR(100),
  Weight		      INT,
  Price			      NUMBER,
  Wired_Wireless 	VARCHAR(100),
  PRIMARY KEY (Model, Colour)
);

CREATE TABLE Keyboard(
  Model	      VARCHAR(100)	PRIMARY KEY,
  Brand		    VARCHAR(100),
  Colour 	    VARCHAR(100),
  Percentage  NUMBER,
  Price      	NUMBER
);

CREATE TABLE Switches(
  Model	  VARCHAR(100),
  Brand		VARCHAR(100),
  Colour	VARCHAR(100),
  Sound	  VARCHAR(100),
  PRIMARY KEY (Model, Brand, Colour, Sound)
);

CREATE TABLE Switches_Price(
  Model	  VARCHAR(100),
  Brand		VARCHAR(100),
  Colour	VARCHAR(100),
  Price		NUMBER,
  PRIMARY KEY (Model, Brand, Colour)
);

CREATE TABLE GPU_Has_Model(
 Model	VARCHAR(100)		PRIMARY KEY,
 Fans		INT
);

CREATE TABLE GPU_Has_Price(
 Model		VARCHAR(100),
 Brand		VARCHAR(100),
 Variant	VARCHAR(100),
 VRAM		  INT,
 Design		VARCHAR(100),
 Fans		  INT,
 Series		VARCHAR(100),
 Price		NUMBER,
 PRIMARY KEY (Model, Brand, Variant, VRAM, Design)
);

CREATE TABLE GPU_Has_Power(
 Model		VARCHAR(100),
 VRAM		  INT,
 Fans		  INT,
 Power		INT,
 PRIMARY KEY (Model, VRAM)
);

CREATE TABLE RAM_Placed_On_Power(
  DDR		    VARCHAR(100),
  Capacity	INT,
  Power		  INT,
  PRIMARY KEY (DDR, Capacity)
);

CREATE TABLE PowerSupply_In_Model(
 Model			VARCHAR(100)    PRIMARY KEY,
 Brand			VARCHAR(100),
 Efficiency	VARCHAR(100),
 Wattage		INT
);

CREATE TABLE Case_Contains(
  Model		      VARCHAR(100),
  Colour		    VARCHAR(100),
  Case_Size			VARCHAR(100),
  Price			    NUMBER,
  PRIMARY KEY (Model, Colour, Case_Size)
);

CREATE TABLE PowerSupply_In(
 Model			  VARCHAR(100),
 Modularity		VARCHAR(100),
 Form_Factor	VARCHAR(100),
 Price			  NUMBER,
 Case_Model		VARCHAR(100),
 Case_Colour 	VARCHAR(100),
 Case_Size		VARCHAR(100),
 PRIMARY KEY(Model, Modularity, Form_Factor),
 FOREIGN KEY(Case_Model, Case_Colour, Case_Size) REFERENCES Case_Contains (Model, Colour, Case_Size) ON DELETE CASCADE
);

CREATE TABLE Motherboard_Powers(
  Model		        VARCHAR(100),
  Form_Factor 		VARCHAR(100),
  Series			    VARCHAR(100),
  WIFI			      VARCHAR(100),
  Brand			      VARCHAR(100),
  Power		        INT,
  Expansion_Slots VARCHAR(100),
  Price			      NUMBER,
  Memory_Slots	  INT, 
  DDR 			      VARCHAR(100),
  Storage_Ports 	INT, 
  PSU_Modularity 	VARCHAR(100)	NOT NULL, 
  PSU_Form_Factor VARCHAR(100)	NOT NULL, 
  PSU_Model 		  VARCHAR(100)	NOT NULL,
  Case_Model		  VARCHAR(100),
  Case_Colour		  VARCHAR(100),
  Case_Size			  VARCHAR(100),
  PRIMARY KEY (Model, Form_Factor, Series, WIFI),
  UNIQUE (PSU_Modularity, PSU_Form_Factor, PSU_Model),
  UNIQUE (Case_Model, Case_Colour, Case_Size),
  FOREIGN KEY (PSU_Modularity, PSU_Form_Factor, PSU_Model) REFERENCES PowerSupply_In (Modularity, Form_Factor, Model) ON DELETE CASCADE,
  FOREIGN KEY (Case_Model, Case_Colour, Case_Size) REFERENCES Case_Contains (Model, Colour, Case_Size) ON DELETE CASCADE
);

CREATE TABLE CPU_On(
 Model			    VARCHAR(100)		PRIMARY KEY, 
 Cores			 	  VARCHAR(100), 
 Generation     INT, 
 Socket				  VARCHAR(100),
 Price			    Number,
 Brand		    	VARCHAR(100),
 MB_Model			  VARCHAR(100),
 MB_Form_Factor VARCHAR(100),
 MB_Series			VARCHAR(100), 
 MB_WIFI			  VARCHAR(100),
 FOREIGN KEY (MB_Model, MB_Form_Factor, MB_Series, MB_WIFI) REFERENCES Motherboard_Powers (Model, Form_Factor, Series, WIFI) on DELETE CASCADE
);

CREATE TABLE CPUCooler_On(
  Model	          VARCHAR(100),
  CPUCooler_Size  INT,
  Price		        NUMBER,
  CPU_Model 	    VARCHAR(100)  UNIQUE,
  PRIMARY KEY (Model, CPUCooler_Size),
  FOREIGN KEY (CPU_Model) REFERENCES CPU_On (Model) ON DELETE CASCADE
 );

CREATE TABLE CaseFan_Inside(
  Model	        VARCHAR(100),
  CaseFan_Size  INT,
  Price		      NUMBER,
  Colour	      VARCHAR(100),
  Case_Model	  VARCHAR(100),
  Case_Colour	  VARCHAR(100),
  Case_Size	    VARCHAR(100),
  PRIMARY KEY (Model, CaseFan_Size),
  FOREIGN KEY (Case_Model, Case_Colour, Case_Size) REFERENCES Case_Contains (Model, Colour, Case_Size) ON DELETE CASCADE
);

CREATE TABLE Storage_Requires(
  Model		        VARCHAR(100),  
  Capacity		    INT,
  Interface		    VARCHAR(100),
  Storage_Type    VARCHAR(100),
  Form_Factor	    VARCHAR(100),
  Power		        INT,
  MB_Model		    VARCHAR(100),
  MB_Form_Factor	VARCHAR(100),
  MB_Series		    VARCHAR(100),
  MB_WIFI		      VARCHAR(100),
  PRIMARY KEY (Model, Capacity, Interface, Storage_Type, Form_Factor),
  FOREIGN KEY (MB_Model, MB_Form_Factor, MB_Series, MB_WIFI) REFERENCES Motherboard_Powers (Model, Form_Factor, Series, WIFI) ON DELETE CASCADE
);

CREATE TABLE GPU_Has(
 Model			    VARCHAR(100),
 VRAM 		      INT,
 Fans 			    INT,
 Brand  		    VARCHAR(100), 
 Variant 		    VARCHAR(100), 
 Design 		    VARCHAR(100),
 Series 		    VARCHAR(100),
 MB_Model		    VARCHAR(100),
 MB_Form_Factor	VARCHAR(100),
 MB_Series		  VARCHAR(100),
 MB_WIFI 		    VARCHAR(100),
 PRIMARY KEY (Model, VRAM, Brand, Variant, Design),
 FOREIGN KEY (MB_Model, MB_Form_Factor, MB_Series, MB_WIFI) REFERENCES Motherboard_Powers (Model, Form_Factor, Series, WIFI) ON DELETE CASCADE
);

CREATE TABLE Monitor_Attached_To_Model(
  Model	  VARCHAR(100)		PRIMARY KEY,
  Brand		VARCHAR(100),
  Display	VARCHAR(100)
 );

 CREATE TABLE Monitor_Attached_To(
  Model		        VARCHAR(100),
  Curvature 	    VARCHAR(100),
  Resolution	    VARCHAR(100),
  Refresh_Rate	  INT,
  Monitor_Size	  NUMBER,
  Response_Time   NUMBER,
  Price			      NUMBER,
  GPU_Model		    VARCHAR(100), 
  GPU_Brand 	    VARCHAR(100), 
  GPU_Variant	    VARCHAR(100),
  GPU_VRAM 		    INT,
  GPU_Design      VARCHAR(100), 
  MB_Model	 	    VARCHAR(100), 
  MB_Form_Factor  VARCHAR(100),
  MB_Series		    VARCHAR(100),
  MB_WIFI		      VARCHAR(100),
  PRIMARY KEY (Model, Curvature, Resolution, Refresh_Rate, Monitor_Size),
  FOREIGN KEY (MB_Model, MB_Form_Factor, MB_Series, MB_WIFI) REFERENCES Motherboard_Powers (Model, Form_Factor, Series, WIFI) ON DELETE CASCADE,
  FOREIGN KEY (GPU_Model, GPU_Brand, GPU_Variant, GPU_VRAM, GPU_Design) REFERENCES GPU_Has (Model, Brand, Variant, VRAM, Design) ON DELETE CASCADE
 );

CREATE TABLE RAM_Placed_On(
  Model		        VARCHAR(100),
  DDR			        VARCHAR(100),
  Capacity		    INT,
  Clock_Speed		  INT,
  Price			      NUMBER,
  MB_Model		    VARCHAR(100),
  MB_Form_Factor	VARCHAR(100),
  MB_Series		    VARCHAR(100),
  MB_WIFI		      VARCHAR(100),
  PRIMARY KEY (Model, DDR, Capacity, Clock_Speed),
  FOREIGN KEY (MB_Model, MB_Form_Factor, MB_Series, MB_WIFI) REFERENCES Motherboard_Powers (Model, Form_Factor, Series, WIFI) ON DELETE CASCADE
);

CREATE TABLE Plugged_Into(
 Case_Model		VARCHAR(100),
 Case_Colour	VARCHAR(100),
 Case_Size		VARCHAR(100),
 Mouse_Model	VARCHAR(100),
 Mouse_Colour	VARCHAR(100),
 PRIMARY KEY (Case_Model, Case_Colour, Case_Size, Mouse_Model, Mouse_Colour),
 FOREIGN KEY (Case_Model, Case_Colour, Case_Size) REFERENCES Case_Contains (Model, Colour, Case_Size) ON DELETE CASCADE,
 FOREIGN KEY (Mouse_Model, Mouse_Colour) REFERENCES Mouse (Model, Colour) ON DELETE CASCADE
);

CREATE TABLE Connected_To(
 Case_Model		    VARCHAR(100),
 Case_Colour		  VARCHAR(100),
 Case_Size			  VARCHAR(100),
 Keyboard_Model		VARCHAR(100),
 PRIMARY KEY (Case_Model, Case_Colour, Case_Size, Keyboard_Model),
 FOREIGN KEY (Case_Model, Case_Colour, Case_Size) REFERENCES Case_Contains (Model, Colour, Case_Size) ON DELETE CASCADE,
 FOREIGN KEY (Keyboard_Model) REFERENCES Keyboard (Model) ON DELETE CASCADE
);


-- Populate all the tables
INSERT INTO CPUCooler_On_Model values ('DeepCool GAMMAXX AG400 BK ARGB', 'RGB', 'Deepcool', 'Air', null);
INSERT INTO CPUCooler_On_Model values ('DeepCool GAMMAXX AG400', null, 'Deepcool', 'Air', null);
INSERT INTO CPUCooler_On_Model values ('DeepCool GAMMAXX AG400 WH ARGB', 'RGB', 'Deepcool', 'Air',  null);
INSERT INTO CPUCooler_On_Model values ('DeepCool GAMMAXX CT', 'RGB', 'Deepcool', 'Air', null);
INSERT INTO CPUCooler_On_Model values ('Corsair iCUE H150i Elite CAPELLIX XT', 'RGB', 'Corsair', 'Liquid', null);

INSERT INTO CaseFan_Inside_Model values('Noctua NF-P12 Redux', 'Noctua', null);
INSERT INTO CaseFan_Inside_Model values('Noctua NF-A20 PWM', 'Noctua', null);
INSERT INTO CaseFan_Inside_Model values('Corsair iCUE QL120', 'Corsair', 'RGB');
INSERT INTO CaseFan_Inside_Model values('Corsair iCUE QL140', 'Corsair', 'RGB');
INSERT INTO CaseFan_Inside_Model values('Corsair LL Series LL120', 'Corsair', 'RGB');
INSERT INTO CaseFan_Inside_Model values('Corsair iCUE AR120', 'Corsair', 'RGB');
INSERT INTO CaseFan_Inside_Model values('Corsair iCUE SP120', 'Corsair', 'RGB');
INSERT INTO CaseFan_Inside_Model values('Corsair SP120 Elite', 'Corsair', 'RGB');
INSERT INTO CaseFan_Inside_Model values('Thermalright TL-C12CW-S', 'Thermalright', null);
INSERT INTO CaseFan_Inside_Model values('Arctic F12 PWM', 'Arctic', 'RGB');

INSERT INTO CPU_On_Power values (6, 5000, 65);
INSERT INTO CPU_On_Power values (8, 7000, 120);
INSERT INTO CPU_On_Power values (16, 13, 125);
INSERT INTO CPU_On_Power values (24, 13, 125);
INSERT INTO CPU_On_Power values (6, 7000, 105);

INSERT INTO Motherboard_Powers_Socket values('AMD Ryzen 5 5600X 6-core', 'Socket AM4');
INSERT INTO Motherboard_Powers_Socket values('AMD Ryzen 5 5500 6-core', 'Socket AM4');
INSERT INTO Motherboard_Powers_Socket values('AMD Ryzen 5 4600G', 'Socket AM4');
INSERT INTO Motherboard_Powers_Socket values('Intel Core i5-12600K', 'LGA 1700');
INSERT INTO Motherboard_Powers_Socket values('AMD Ryzen 7 5700X', 'Socket AM4');

INSERT INTO Storage_Requires_RW_Speeds values ('IronWolf Pro 12', 250, 250, 'Seagate');
INSERT INTO Storage_Requires_RW_Speeds values ('WD Red Plus', 160, 120, 'Western Digital');
INSERT INTO Storage_Requires_RW_Speeds values ('870 EVO SATA III', 560, 530, 'Samsung');
INSERT INTO Storage_Requires_RW_Speeds values ('WD Blue SA510', 560, 540, 'Western Digital');
INSERT INTO Storage_Requires_RW_Speeds values ('WD_Black SN770', 5150, 4850, 'Western Digital');

INSERT INTO Storage_Requires_Price values ('IronWolf Pro 12', 12, 'HDD', 269.99);
INSERT INTO Storage_Requires_Price values ('WD Red Plus', 6, 'HDD', 149.99);
INSERT INTO Storage_Requires_Price values ('870 EVO SATA III', 1, 'SSD', 59.99);
INSERT INTO Storage_Requires_Price values ('WD Blue SA510', 2, 'SSD', 129.99);
INSERT INTO Storage_Requires_Price values ('WD_Black SN770', 2, 'SSD', 99.99);

INSERT INTO RAM_Placed_On_Brand values('VENGEANCE LPX', 'Corsair');
INSERT INTO RAM_Placed_On_Brand values('VENGEANCE RGB PRO SL', 'Corsair');
INSERT INTO RAM_Placed_On_Brand values('VENGEANCE LPX PRO', 'Corsair');
INSERT INTO RAM_Placed_On_Brand values('VENGEANCE DDR5', 'Corsair');
INSERT INTO RAM_Placed_On_Brand values('VENGEANCE SODIMM', 'Corsair');

INSERT INTO Case_Contains_Brand values('Corsair 4000D CC-9011200-WW', 'Corsair');
INSERT INTO Case_Contains_Brand values('Corsair 4000D CC-9011201-WW', 'Corsair');
INSERT INTO Case_Contains_Brand values('Corsair iCUE 4000X RGB CC-9011204-WW', 'Corsair');
INSERT INTO Case_Contains_Brand values('Corsair iCUE 4000X RGB CC-9011205-WW', 'Corsair');
INSERT INTO Case_Contains_Brand values('DeepCool CC560 Mid-Tower ATX', 'DeepCool');
INSERT INTO Case_Contains_Brand values('Thermaltake Versa H17', 'Thermaltake');

INSERT INTO Mouse values('G305 LIGHTSPEED', 'Black', 'Logitech', 'Standard', 99, 49.99, 'Wireless');
INSERT INTO Mouse values('G502 HERO', 'Black', 'Logitech', 'Standard', 89, 79.99, 'Wired');
INSERT INTO Mouse values('Basilisk V3', 'Black', 'Razer', 'Ergonomic', 128, 69.99, 'Wired');
INSERT INTO Mouse values('DeathAdder', 'Black', 'Razer', 'Standard', 96, 29.99, 'Wired');
INSERT INTO Mouse values('SCIMITAR RGB ELITE', 'Black', 'Corsair', 'Standard', 113, 189.99, 'Wireless');
INSERT INTO Mouse values('M65 RGB Ultra Tunable', 'White', 'Corsair', 'Standard', 97, 79.99, 'Wired');
INSERT INTO Mouse values('G305 LIGHTSPEED', 'White', 'Logitech', 'Standard', 99, 49.99, 'Wireless');
INSERT INTO Mouse values('M711 Cobra RGB', 'White', 'Redragon', 'Standard', 144, 29.99, 'Wired');

INSERT INTO Keyboard values('Corsair K55 Pro Lite', 'Corsair', 'Black', 100.00, 74.95);
INSERT INTO Keyboard values('Corsair K70 Pro Mini Wireless', 'Corsair', 'Blue', 60.00, 259.99);
INSERT INTO Keyboard values('Corsair K100 Air Wireless RGB Ultra-Thin', 'Corsair', 'Black', 100.00, 399.99);
INSERT INTO Keyboard values('Corsair K100 RGB Optical-Mechanical', 'Corsair', 'White', 100.00, 349.99);
INSERT INTO Keyboard values('Logitech G815 LIGHTSYNC RGB Mecahnical W', 'Logitech', 'White', 100.00, 199.99);
INSERT INTO Keyboard values('Logitech G815 LIGHTSYNC RGB Mecahnical', 'Logitech', 'Black', 100.00, 199.99);
INSERT INTO Keyboard values('Havit Mechanical Keyboard 89 Keys', 'Havit', 'Black', 85.00, 42.99);

INSERT INTO Switches values('Oil King Axis Pre Lubricated', 'Gateron', 'Black', 'Linear');
INSERT INTO Switches values('Panda Switch Lubed', 'Glorious Gaming', 'Orange', 'Tactile');
INSERT INTO Switches values('Panda Switch Unlubed', 'Glorious Gaming', 'Orange', 'Tactile');
INSERT INTO Switches values('Wisteria 39gf Linear', 'EPOMAKER', 'Wisteria', 'Linear');
INSERT INTO Switches values('Dawn Pink 38gf Linear', 'EPOMAKER', 'Dawn Pink', 'Linear');

INSERT INTO Switches_Price values('Oil King Axis Pre Lubricated', 'Gateron', 'Black', 28.90);
INSERT INTO Switches_Price values('Panda Switch Lubed', 'Glorious Gaming', 'Orange', 54.99);
INSERT INTO Switches_Price values('Panda Switch Unlubed', 'Glorious Gaming', 'Orange', 24.99);
INSERT INTO Switches_Price values('Wisteria 39gf Linear', 'EPOMAKER', 'Wisteria', 11.99);
INSERT INTO Switches_Price values('Dawn Pink 38gf Linear', 'EPOMAKER', 'Dawn Pink', 11.99);

INSERT INTO GPU_Has_Model values('RTX 3060', 3);
INSERT INTO GPU_Has_Model values('RTX 2060', 3);
INSERT INTO GPU_Has_Model values('GTX 1050', 3);
INSERT INTO GPU_Has_Model values('GTX 980', 2);
INSERT INTO GPU_Has_Model values('RX 6800', 3);

INSERT INTO GPU_Has_Price values('RTX 2060', 'MSI', 'OC', 6, 'Black', 3, 'MSI GeForce RTX 2060',  650);
INSERT INTO GPU_Has_Price values('RTX 2060', 'ASUS', 'OC', 12, 'Black', 3, 'Dual NVIDIA GeForce RTX 2060',  500);
INSERT INTO GPU_Has_Price values('GTX 1050', 'ASUS', 'TI', 4, 'Black', 1, 'ASUS PH-GTX 1050',  230);
INSERT INTO GPU_Has_Price values('GTX 1050', 'MSI', 'OC TI', 4, 'Black', 2, 'MSI GTX 1050',  450);
INSERT INTO GPU_Has_Price values('GTX 1050', 'MSI', 'TI', 4, 'Red', 2, 'MSI GTX 1050',  380);

INSERT INTO GPU_Has_Power values('RTX 3060', 8, 3, 170);
INSERT INTO GPU_Has_Power values('GTX 1060', 6, 3, 100);
INSERT INTO GPU_Has_Power values('RTX 2060', 16, 3, 190);
INSERT INTO GPU_Has_Power values('RTX 4060', 16, 3, 160);
INSERT INTO GPU_Has_Power values('GTX 1050', 8, 3, 75);

INSERT INTO RAM_Placed_On_Power values('DDR4', 32, 12);
INSERT INTO RAM_Placed_On_Power values('DDR5', 32, 16);

INSERT INTO PowerSupply_In_Model values('RM750e', 'Corsair', '80+ Gold', 750);
INSERT INTO PowerSupply_In_Model values('RM1000x', 'Corsair', '80+ Gold', 1000);
INSERT INTO PowerSupply_In_Model values('Toughpower GX2', 'Thermaltake', '80+ Gold', 600);
INSERT INTO PowerSupply_In_Model values('SF750', 'Corsair', '80+ Platinum', 1200);
INSERT INTO PowerSupply_In_Model values('CX650M', 'Corsair', '80+ Bronze', 650);

INSERT INTO Case_Contains values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 119.98);
INSERT INTO Case_Contains values('Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower', 119.99);
INSERT INTO Case_Contains values('Corsair iCUE 4000X RGB CC-9011204-WW', 'Black', 'Mid-Tower', 159.99);
INSERT INTO Case_Contains values('Corsair iCUE 4000X RGB CC-9011205-WW', 'White', 'Mid-Tower', 189.99);
INSERT INTO Case_Contains values('DeepCool CC560 Mid-Tower ATX', 'Black', 'Mid-Tower', 99.99);
INSERT INTO Case_Contains values('Thermaltake Versa H17', 'Black', 'Mini-Tower', 64.99);

INSERT INTO PowerSupply_In values('RM750e', 'Full', 'ATX', 100, 'Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower');
INSERT INTO PowerSupply_In values('RM1000x', 'Full', 'ATX', 170, 'Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower');
INSERT INTO PowerSupply_In values('ToughPower GX2', 'Semi', 'ATX', 65, 'Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower');
INSERT INTO PowerSupply_In values('CX650M', 'Semi', 'ATX', 70, 'Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower');
INSERT INTO PowerSupply_In values('RM1000e', 'Full', 'ATX', 160, 'Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower');

INSERT INTO Motherboard_Powers values('Pro Z690-A', 'ATX', 'Pro Z Series', 'No', 'MSI', 50, null, 299.99, 4, 'DDR4', 3,  'Full', 'ATX', 'RM750e', null, null, null);
INSERT INTO Motherboard_Powers values('Prime B660M-A D4', 'mATX', 'Prime B660M-A D4', 'No', 'ASUS', 50, null, 169.99, 2, 'DDR4', 3,  'Full', 'ATX', 'RM1000x', null, null, null);
INSERT INTO Motherboard_Powers values('MAG Z690 TOMAHAWK WIFI', 'ATX', 'Tomahawk Series', 'Yes', 'MSI', 80, null, 299.99, 4, 'DDR5', 5, 'Semi', 'ATX', 'ToughPower GX2', null, null, null);
INSERT INTO Motherboard_Powers values('MSI Z690I UNIFY', 'Mini ITX', 'Z Series', 'No', 'MSI', 60, null, 519.99, 5, 'DDR5', 5, 'Semi', 'ATX', 'CX650M', null, null, null);
INSERT INTO Motherboard_Powers values('Pro Z690-P', 'ATX', 'Pro Z Series', 'No', 'MSI', 50, null, 277.99, 4, 'DDR4', 4, 'Full', 'ATX', 'RM1000e', null, null, null);

INSERT INTO CPU_On values('Ryzen 5 5600X', 6, 5000, 'AM4', 160, 'AMD', null, null, null, null);
INSERT INTO CPU_On values('Ryzen 7 7800X3D', 8, 7000, 'AM5', 370, 'AMD', null, null, null, null);
INSERT INTO CPU_On values('Ryzen 7 5800X', 8, 5000, 'AM4', 160, 'AMD', null, null, null, null);
INSERT INTO CPU_On values('Intel Core i7-13700K', 16, 13, 'LGA 1700', 360, 'Intel', null, null, null, null);
INSERT INTO CPU_On values('Intel Core i9-13900K', 24, 13, 'LGA 1700', 570, 'Intel', null, null, null, null);

INSERT INTO CPUCooler_On values('DeepCool GAMMAXX AG400 BK ARGB', 120, 39.99, null);
INSERT INTO CPUCooler_On values('DeepCool GAMMAXX AG400', 120, 34.99, null);
INSERT INTO CPUCooler_On values('DeepCool GAMMAXX AG400 WH ARGB', 120, 39.99, null);
INSERT INTO CPUCooler_On values('DeepCool GAMMAXX CT', 120, 49.99, null);
INSERT INTO CPUCooler_On values('Corsair iCUE H150i Elite CAPELLIX XT', 360, 284.99, null);

INSERT INTO CaseFan_Inside values('Noctua NF-P12 Redux', 120, 15.95, 'grey', 'Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Noctua NF-A20 PWM', 200, 39.64, 'brown', 'Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Corsair iCUE QL120', 120, 44.99,'black', 'Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Corsair iCUE QL140', 140, 49.99,'black', 'DeepCool CC560 Mid-Tower ATX', 'Black', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Corsair LL Series LL120', 120, 25.99, 'black',  'Thermaltake Versa H17', 'Black', 'Mini-Tower');
INSERT INTO CaseFan_Inside values('Corsair iCUE AR120', 120, 21.74, 'black',  'DeepCool CC560 Mid-Tower ATX', 'Black', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Corsair iCUE SP120', 120, 44.99, 'white',  'Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Corsair SP120 Elite', 120, 14.99, 'black',  'Thermaltake Versa H17', 'Black', 'Mini-Tower');
INSERT INTO CaseFan_Inside values('Thermalright TL-C12CW-S', 120, 17.90, 'white',  'DeepCool CC560 Mid-Tower ATX', 'Black', 'Mid-Tower');
INSERT INTO CaseFan_Inside values('Arctic F12 PWM', 120, 15.99, 'white', 'Thermaltake Versa H17', 'Black', 'Mini-Tower');

INSERT INTO Storage_Requires values('IronWolf Pro 12', 12, 'SATA', 'HDD', '3.5 Inch', 9, null, null, null, null);
INSERT INTO Storage_Requires values('WD Read Plus', 6, 'SATA', 'HDD', '3.5 Inch', 9, null, null, null, null);
INSERT INTO Storage_Requires values('870 EVO SATA III', 1, 'SATA', 'SSD', '2.5 Inch', 16, null, null, null, null);
INSERT INTO Storage_Requires values('WD Blue SA510', 2, 'SATA', 'SSD', '2.5 Inch', 16, null, null, null, null);
INSERT INTO Storage_Requires values('WD_Black SN770', 2, 'NVMe', 'SSD', 'M.2', 1, null, null, null, null);

INSERT INTO GPU_Has values('RTX 3060', 16, 3, 'ASUS', 'Ti', 'Black',  'ASUS TUF GeForce RTX 3060', null, null, null, null);
INSERT INTO GPU_Has values('RTX 3050', 16, 3, 'ASUS', 'OC Ti', 'Black',  'ASUS TUF GeForce RTX 3050', null, null, null, null);
INSERT INTO GPU_Has values('RTX 4060', 16, 3, 'MSI', 'Ti', 'Black',  'MSI GeForce RTX 4060', null, null, null, null);
INSERT INTO GPU_Has values('GTX 1050', 16, 3, 'MSI', 'Ti', 'Black',  'MSI GeForce RTX 1050', null, null, null, null);
INSERT INTO GPU_Has values('GTX 1050', 8, 2, 'MSI', 'OC', 'Black',  'MSI GTX 1050', null, null, null, null);

INSERT INTO Monitor_Attached_To_Model values('LG 24MP40A-C Full HD LCD', 'LG', 'LCD');
INSERT INTO Monitor_Attached_To_Model values('Samsung LS24R350FZNXZA LED-Lit Monitor', 'Samsung', 'LED');
INSERT INTO Monitor_Attached_To_Model values('Samsung LS24T350FHNXZA LED-Lit Monitor', 'Samsung', 'LED');
INSERT INTO Monitor_Attached_To_Model values('Alienware AW3423DWF QD-OLED LED', 'Alienware', 'OLED');
INSERT INTO Monitor_Attached_To_Model values('ViewSonic Elite XG270', 'ViewSonic', 'LED');

INSERT INTO Monitor_Attached_To values('Alienware AW3423DWF QD-OLED LED', '1800R', '3440 x 1440', 165, 34, 0.1, 999.99, null, null, null, null, null, null, null, null, null);
INSERT INTO Monitor_Attached_To values('Samsung LS32B200NWNXGO LED Monitor', '4000R', '1920 x 1080', 75, 32, 8, 199.99, null, null, null, null, null, null, null, null, null);
INSERT INTO Monitor_Attached_To values('Samsung LS24T350FHNXZA LED-Lit Monitor', '4000R', '1920 x 1080', 75, 24, 5, 128.00, null, null, null, null, null, null, null, null, null);
INSERT INTO Monitor_Attached_To values('Samsung LS24R350FZNXZA LED-Lit Monitor', '4000R', '1920 x 1080', 75, 24, 5, 128.00, null, null, null, null, null, null, null, null, null);
INSERT INTO Monitor_Attached_To values('ViewSonic Elite XG270 LED', '4000R', '1920 x 1080', 240, 27, 1, 691.00, null, null, null, null, null, null, null, null, null);

INSERT INTO RAM_Placed_On values('VENGEANCE LPX', 'DDR4', 32, 3200, 114.00, null, null, null, null);
INSERT INTO RAM_Placed_On values('VENGEANCE RGB PRO SL', 'DDR4', 32, 3600, 134.99, null, null, null, null);
INSERT INTO RAM_Placed_On values('VENGEANCE RGB PRO', 'DDR4', 32, 3600, 79.99, null, null, null, null);
INSERT INTO RAM_Placed_On values('VENGEANCE DDR5', 'DDR5', 32, 5600, 94.66, null, null, null, null);
INSERT INTO RAM_Placed_On values('VENGEANCE SODIMM', 'DDR5', 32, 4800, 94.99, null, null, null, null);

INSERT INTO Plugged_Into values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'G305 LIGHTSPEED', 'Black');
INSERT INTO Plugged_Into values('Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower', 'G502 HERO', 'Black');
INSERT INTO Plugged_Into values('Corsair iCUE 4000X RGB CC-9011204-WW', 'Black', 'Mid-Tower', 'Basilisk V3', 'Black');
INSERT INTO Plugged_Into values('Corsair iCUE 4000X RGB CC-9011205-WW', 'White', 'Mid-Tower', 'DeathAdder', 'Black');
INSERT INTO Plugged_Into values('Thermaltake Versa H17', 'Black', 'Mini-Tower', 'DeathAdder', 'Black');

INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Corsair K55 Pro Lite');
INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Corsair K70 Pro Mini Wireless');
INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Corsair K100 Air Wireless RGB Ultra-Thin');
INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Corsair K100 RGB Optical-Mechanical');
INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Logitech G815 LIGHTSYNC RGB Mecahnical W');
INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Logitech G815 LIGHTSYNC RGB Mecahnical');
INSERT INTO Connected_To values('Corsair 4000D CC-9011200-WW', 'Black', 'Mid-Tower', 'Havit Mechanical Keyboard 89 Keys');
INSERT INTO Connected_To values('Corsair 4000D CC-9011201-WW', 'White', 'Mid-Tower', 'Corsair K70 Pro Mini Wireless');
INSERT INTO Connected_To values('Corsair iCUE 4000X RGB CC-9011204-WW', 'Black', 'Mid-Tower', 'Corsair K100 Air Wireless RGB Ultra-Thin');
INSERT INTO Connected_To values('Corsair iCUE 4000X RGB CC-9011205-WW', 'White', 'Mid-Tower', 'Corsair K100 RGB Optical-Mechanical');





