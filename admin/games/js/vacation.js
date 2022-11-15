/*
 * Program - Country Guessing Game
 * File Name - script.js
 * Author - ShuvoRim
 * Web site - http://www.shuvorim.tk
 * Email - shuvorim@hotmail.com
 * (c)ShuvoRim Pvt. Ltd. 2002 - 03
 * All rights reserved.
 * ------------------------------
 * Visit our web site for free open
 * source Applications, Applets,
 * Scripts and Games. Thank you for
 * using our program.
 */

var country = new Array(180);
country[0] = "AFGHANISTAN";
country[1] = "AFRICA";
country[2] = "ALBANIA";
country[3] = "ALGERIA";
country[4] = "AMERICA";
country[5] = "ANDORRA";
country[6] = "ANGOLA";
country[7] = "ANTARCTICA";
country[8] = "ARGENTINA";
country[9] = "ARMENIA";
country[10] = "AUSTRALIA";
country[11] = "AUSTRIA";
country[12] = "AZERBAIJAN";
country[13] = "BAHAMAS";
country[14] = "BAHRAIN";
country[15] = "BANGLADESH";
country[16] = "BARBADOS";
country[17] = "BELARUS";
country[18] = "BELGIUM";
country[19] = "BELIZE";
country[20] = "BENIN";
country[21] = "BHUTAN";
country[22] = "BOLIVIA";
country[23] = "BOSNIA HERZEGOVINA";
country[24] = "BOTSWANA";
country[25] = "BRAZIL";
country[26] = "BRUNEI";
country[27] = "BULGARIA";
country[28] = "BURKINA";
country[29] = "BURUNDI";
country[30] = "CAMBODIA";
country[31] = "CAMEROON";
country[32] = "CANADA";
country[33] = "CAPE VERDE ISLANDS";
country[34] = "CHAD";
country[35] = "CHILE";
country[36] = "CHINA";
country[37] = "COLOMBIA";
country[38] = "COMOROS";
country[39] = "CONGO";
country[40] = "COSTA RICA";
country[41] = "CROATIA";
country[42] = "CUBA";
country[43] = "CYPRUS";
country[44] = "CZECH REPUBLIC";
country[45] = "DENMARK";
country[46] = "DJIBOUTI";
country[47] = "DOMINICAN REPUBLIC";
country[48] = "ECUADOR";
country[49] = "EGYPT";
country[50] = "EL SALVADOR";
country[51] = "ERITREA";
country[52] = "ESTONIA";
country[53] = "ETHIOPIA";
country[54] = "EUROPE";
country[55] = "FIJI";
country[56] = "FINLAND";
country[57] = "FRANCE";
country[58] = "GABON";
country[59] = "GAMBIA";
country[60] = "GEORGIA";
country[61] = "GERMANY";
country[62] = "GHANA";
country[63] = "GREECE";
country[64] ="GRENADA";
country[65] = "GUATEMALA";
country[66] = "GUINEA";
country[67] = "HAITI";
country[68] = "HOLLAND";
country[69] = "HONDURAS";
country[70] = "HONG KONG";
country[71] = "HUNGARY";
country[72] = "ICELAND";
country[73] = "INDIA";
country[74] = "INDONESIA";
country[75] = "IRAN";
country[76] = "IRAQ";
country[77] = "ISRAEL";
country[78] = "ITALY";
country[79] = "JAMAICA";
country[80] = "JAPAN";
country[81] = "JORDAN";
country[82] = "KAZAKHSTAN";
country[83] = "KENYA";
country[84] = "KIRGYZSTAN";
country[85] = "KIRIBATI";
country[86] = "KOREA";
country[87] = "KUWAIT";
country[88] = "LAOS";
country[89] = "LATVIA";
country[90] = "LEBANON";
country[91] = "LESOTHO";
country[92] = "LIBERIA";
country[93] = "LIBYA";
country[94] = "LIECHTENSTEIN";
country[95] = "LITHUANIA";
country[96] = "LUXEMBOURG";
country[97] = "MADAGASCAR";
country[98] = "MALAWI";
country[99] = "MALAYSIA";
country[100] = "MALDIVES";
country[101] = "MALI";
country[102] = "MALTA";
country[103] = "MAURITANIA";
country[104] = "MAURITIUS";
country[105] = "MEXICO";
country[106] = "MOLDOVA";
country[107] = "MONACO";
country[108] = "MONGOLIA";
country[109] = "MONTSERRAT";
country[110] = "MOROCCO";
country[111] = "MOZAMBIQUE";
country[112] = "MYANMAR";
country[113] = "NAMIBIA";
country[114] = "NAURU";
country[115] = "NEPAL";
country[116] = "NETHERLANDS";
country[117] = "NEW ZEALAND";
country[118] = "NICARAGUA";
country[119] = "NIGERIA";
country[120] = "NORWAY";
country[121] = "OMAN";
country[122] = "PAKISTAN";
country[123] = "PANAMA";
country[124] = "PAPUA NEW GUINEA";
country[125] = "PARAGUAY";
country[126] = "PERU";
country[127] = "PHILIPPINES";
country[128] = "POLAND";
country[129] = "PORTUGAL";
country[130] = "QATAR";
country[131] = "ROMANIA";
country[132] = "RUSSIA";
country[133] = "RWANDA";
country[134] = "SAN MARINO";
country[135] = "SAUDI ARABIA";
country[136] = "SENEGAL";
country[137] = "SEYCHELLES";
country[138] = "SIERRA LEONE";
country[139] = "SINGAPORE";
country[140] = "SLOVAKIA";
country[141] = "SLOVENIA";
country[142] = "SOLOMON ISLANDS";
country[143] = "SOMALIA";
country[144] = "SOUTH AFRICA";
country[145] = "SPAIN";
country[146] = "SRI LANKA";
country[147] = "SUDAN";
country[148] = "SURINAM";
country[149] = "SWAZILAND";
country[150] = "SWEDEN";
country[151] = "SWITZERLAND";
country[152] = "SYRIA";
country[153] = "TAIWAN";
country[154] = "TAJIKITAN";
country[155] = "TANZANIA";
country[156] = "THAILAND";
country[157] = "TOGO";
country[158] = "TONGA";
country[159] = "TRINIDAD";
country[160] = "TUNISIA";
country[161] = "TURKEY";
country[162] = "TURKMENISTAN";
country[163] = "TUVALU";
country[164] = "UGANDA";
country[165] = "UKRAINE";
country[166] = "UNITED ARAB EMIRATES";
country[167] = "URUGUAY";
country[168] = "UZBEKISTAN";
country[169] = "VANUATU";
country[170] = "VATICAN CITY";
country[171] = "VENEZUELA";
country[172] = "VIETNAM";
country[173] = "WEST INDIES";
country[174] = "WESTERN SAMOA";
country[175] = "YEMEN REPUBLIC";
country[176] = "YUGOSLAVIA";
country[177] = "ZAIRE";
country[178] = "ZAMBIA";
country[179] = "ZIMBABWE";

var sr = Math.floor(Math.random() * 180);
var temp = country[sr];
var tries = 0;

function guessit()
{
	var guess = document.vacation.answer.value;
	tries++;
	window.status = "Tries : " + tries + " .";

	switch( tries )
	{
		case 1:
		document.vacation.hint.value = "First Hint : The country name starts with " + temp.charAt(0);
		break;

		case 2:
		document.vacation.hint.value = "Second Hint : The country name ends with " + temp.charAt(temp.length - 1);
		break;

		case 3:
		document.vacation.hint.value = "Last Hint : The country name has " + temp.length + " characters";
		break;

		default:
		document.vacation.hint.value = "No hints are available";
	}

	if( guess.toUpperCase() == temp )	/* if guess equals to temp */
	{
		/* if(window.confirm("Absolutely right! Liffy will visit " + temp + " next!\nClick [okay] to get your rewards!")) */
		document.location="/games.php?play=vacation&go=prize";
	}

	else
	{
		if( tries == 5 ) /* game over */
		{
			/* if(window.confirm("Sorry! Your chances over. The country was  " + temp + "\nDo you want to play again?")) {
			window.location.reload();	/* reloads the page for a new game */
			/* document.vacation.hint.value = "Enter your guess below and click on Guess!";
			} */
			document.location="/games.php?play=vacation&go=lost";
		}
	}
}

function catchKeyCode() /*calls when the user press the RETURN key*/
{
	if( event.keyCode == 13 )
	guessit();
}

function stat()
{
	window.status = "Tries : " + tries + " .";
}

function clearBox()
{
	document.vacation.answer.value = "";
}

function newGame()
{
	if( window.confirm("Do you want to start a new game?") )
	{
		window.location.reload();	/* reloads the page for a new game */
		document.vacation.hint.value = "Enter your guess below and click on Guess!";
	}
}