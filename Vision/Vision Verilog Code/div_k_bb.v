// megafunction wizard: %LPM_DIVIDE%VBB%
// GENERATION: STANDARD
// VERSION: WM1.0
// MODULE: LPM_DIVIDE 

// ============================================================
// File Name: div_k.v
// Megafunction Name(s):
// 			LPM_DIVIDE
//
// Simulation Library Files(s):
// 			lpm
// ============================================================
// ************************************************************
// THIS IS A WIZARD-GENERATED FILE. DO NOT EDIT THIS FILE!
//
// 18.1.0 Build 625 09/12/2018 SJ Lite Edition
// ************************************************************

//Copyright (C) 2018  Intel Corporation. All rights reserved.
//Your use of Intel Corporation's design tools, logic functions 
//and other software and tools, and its AMPP partner logic 
//functions, and any output files from any of the foregoing 
//(including device programming or simulation files), and any 
//associated documentation or information are expressly subject 
//to the terms and conditions of the Intel Program License 
//Subscription Agreement, the Intel Quartus Prime License Agreement,
//the Intel FPGA IP License Agreement, or other applicable license
//agreement, including, without limitation, that your use is for
//the sole purpose of programming logic devices manufactured by
//Intel and sold by Intel or its authorized distributors.  Please
//refer to the applicable agreement for further details.

module div_k (
	clken,
	clock,
	denom,
	numer,
	quotient,
	remain);

	input	  clken;
	input	  clock;
	input	[9:0]  denom;
	input	[17:0]  numer;
	output	[17:0]  quotient;
	output	[9:0]  remain;

endmodule

// ============================================================
// CNX file retrieval info
// ============================================================
// Retrieval info: PRIVATE: INTENDED_DEVICE_FAMILY STRING "MAX 10"
// Retrieval info: PRIVATE: PRIVATE_LPM_REMAINDERPOSITIVE STRING "TRUE"
// Retrieval info: PRIVATE: PRIVATE_MAXIMIZE_SPEED NUMERIC "-1"
// Retrieval info: PRIVATE: SYNTH_WRAPPER_GEN_POSTFIX STRING "0"
// Retrieval info: PRIVATE: USING_PIPELINE NUMERIC "1"
// Retrieval info: PRIVATE: VERSION_NUMBER NUMERIC "2"
// Retrieval info: PRIVATE: new_diagram STRING "1"
// Retrieval info: LIBRARY: lpm lpm.lpm_components.all
// Retrieval info: CONSTANT: LPM_DREPRESENTATION STRING "UNSIGNED"
// Retrieval info: CONSTANT: LPM_HINT STRING "LPM_REMAINDERPOSITIVE=TRUE"
// Retrieval info: CONSTANT: LPM_NREPRESENTATION STRING "UNSIGNED"
// Retrieval info: CONSTANT: LPM_PIPELINE NUMERIC "3"
// Retrieval info: CONSTANT: LPM_TYPE STRING "LPM_DIVIDE"
// Retrieval info: CONSTANT: LPM_WIDTHD NUMERIC "10"
// Retrieval info: CONSTANT: LPM_WIDTHN NUMERIC "18"
// Retrieval info: USED_PORT: clken 0 0 0 0 INPUT NODEFVAL "clken"
// Retrieval info: USED_PORT: clock 0 0 0 0 INPUT NODEFVAL "clock"
// Retrieval info: USED_PORT: denom 0 0 10 0 INPUT NODEFVAL "denom[9..0]"
// Retrieval info: USED_PORT: numer 0 0 18 0 INPUT NODEFVAL "numer[17..0]"
// Retrieval info: USED_PORT: quotient 0 0 18 0 OUTPUT NODEFVAL "quotient[17..0]"
// Retrieval info: USED_PORT: remain 0 0 10 0 OUTPUT NODEFVAL "remain[9..0]"
// Retrieval info: CONNECT: @clken 0 0 0 0 clken 0 0 0 0
// Retrieval info: CONNECT: @clock 0 0 0 0 clock 0 0 0 0
// Retrieval info: CONNECT: @denom 0 0 10 0 denom 0 0 10 0
// Retrieval info: CONNECT: @numer 0 0 18 0 numer 0 0 18 0
// Retrieval info: CONNECT: quotient 0 0 18 0 @quotient 0 0 18 0
// Retrieval info: CONNECT: remain 0 0 10 0 @remain 0 0 10 0
// Retrieval info: GEN_FILE: TYPE_NORMAL div_k.v TRUE
// Retrieval info: GEN_FILE: TYPE_NORMAL div_k.inc FALSE
// Retrieval info: GEN_FILE: TYPE_NORMAL div_k.cmp FALSE
// Retrieval info: GEN_FILE: TYPE_NORMAL div_k.bsf FALSE
// Retrieval info: GEN_FILE: TYPE_NORMAL div_k_inst.v FALSE
// Retrieval info: GEN_FILE: TYPE_NORMAL div_k_bb.v TRUE
// Retrieval info: LIB_FILE: lpm
