<html>
<head>
<title>ROOT usage</title>
<link rel="stylesheet" type="text/css" href="pythia.css"/>
<link rel="shortcut icon" href="pythia32.gif"/>
</head>
<body>

<script language=javascript type=text/javascript>
function stopRKey(evt) {
var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target :((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=="text"))
{return false;}
}

document.onkeypress = stopRKey;
</script>
<?php
if($_POST['saved'] == 1) {
if($_POST['filepath'] != "files/") {
echo "<font color='red'>SETTINGS SAVED TO FILE</font><br/><br/>"; }
else {
echo "<font color='red'>NO FILE SELECTED YET.. PLEASE DO SO </font><a href='SaveSettings.php'>HERE</a><br/><br/>"; }
}
?>

<form method='post' action='ROOTusage.php'>

<h2>ROOT usage</h2>

(This text has been contributed by Andreas Morsch, the second example
by Rene Brun.)

<h3>Interfaces</h3>

<a href="http://root.cern.ch/">ROOT</a> provides two simple interfaces 
(wrappers) for PYTHIA 8. Both are located in the
<pre>
    yourROOTinstallationPath/montecarlo/pythia8
</pre>
directory. (Type <code>which root</code> if you want to find out where 
ROOT has been installed. It will print
<code>yourROOTinstallationPath/bin/root</code>).

<ul>
<li><a href="http://root.cern.ch/root/html/TPythia8.html">
<code>TPythia8</code></a> is an implementation of the
<a href="http://root.cern.ch/root/html/TGenerator.html">
<code>TGenerator</code></a> interface for PYTHIA 8. 
<br/>It allows you to use PYTHIA within a ROOT macro or as a plug-in 
for a general-purpose particle generator based on this interface. The 
main methods of the interface are 
<br/><code>* TPythia8::GenerateEvent()</code> which triggers the generation 
of the next event, and 
</br><code>* TPythia8::ImportParticles(TClonesArray* particles)</code> 
which copies the native PYTHIA stack into a 
<a href="http://root.cern.ch/root/html/TClonesArray.html">
<code>TClonesArray</code></a> of 
<a href="http://root.cern.ch/root/html/TParticle.html">
<code>TParticles</code></a>. 
</br>In addition, we implemented some methods that are directly related 
to corresponding PYTHIA methods:
<pre>
* ReadString(const char* string) -> readString(...)
* ReadConfigFile(const char* string) -> readFile(...)
* Initialize(int idAin, int idBin, double ecms) -> init(...)
* EventListing() -> event.list()
* PrintStatistic() -> statistics()
</pre>
    These methods provide already the basic PYTHIA functionality 
interactively from the ROOT command line. However, this does not mean 
that the usage of PYTHIA from within ROOT is restricted to these methods. 
In compiled code, one can always obtain a pointer to the 
<code>Pythia</code> instance via
<pre>
    Pythia8::Pythia* pythia8 = TPythia8::Pythia8();
</pre>
giving access to the full PYTHIA functionality.
</li>
<li><a href="http://root.cern.ch/root/html/TPythia8Decayer.html">
<code>TPythia8Decayer</code></a> is an implementation of the 
<a href="http://root.cern.ch/root/html/TVirtualMCDecayer.html">
<code>TVirtualMCDecayer</code></a> interface.
<br/>It allows you to use PYTHIA as a plug-in decayer for simulation 
frameworks based on the Virtual Monte Carlo 
(<a href="http://root.cern.ch/drupal/content/vmc">VMC</a>) interface 
classes. The main methods of the interface are 
<br/><code>* TPythia8Decayer::Init()</code> for initialisation, 
<br/><code>* TPythia8Decayer::Decay(Int_t pdg, TLorentzVector*< p)</code> 
to decay a particle with PDG code <code>pdg</code> and 
<a href="http://root.cern.ch/root/html/TLorentzVector.html">
4-momentum</a> <code>p</code>, and
<br/><code>* ImportParticles(TClonesArray* particles)</code> 
to retrieve the decay products as 
<a href="http://root.cern.ch/root/html/TParticle.html">
<code>TParticles</code></a> in the 
<code><a href="http://root.cern.ch/root/html/TClonesArray.html">
TClonesArray</a> particles</code>.
</li>
</ul>

<h3>Installation of ROOT with PYTHIA 8 support</h3>

In order to use PYTHIA 8 with ROOT you need a ROOT version that has been 
<a href="http://root.cern.ch/drupal/content/installing-root-source">
installed from source</a>. The reason is that the interfaces depend on 
PYTHIA header files that are not distributed with ROOT. Installing ROOT 
is not more difficult than the PYTHIA installation.

<p/>
Define an environment variable for the path to your <code>pythia8</code> 
installation directory
<pre>
    export PYTHIA8=YourPathToPythia8
</pre>
Before compiling ROOT 
<a href="http://root.cern.ch/drupal/content/installing-root-source">
configure ROOT</a> running the 
<code>yourROOTinstallationPath/configure</code> command 
including the following options:
<pre>
    --enable-pythia8 
    --with-pythia8-incdir=$PYTHIA8/include 
    --with-pythia8-libdir=$PYTHIA8/lib
</pre>
In case ROOT has already been compiled before, it will only recompile 
the <code>pythia8</code> module and build the library 
<code>libEGPythia8</code>.

<h3>An example</h3>

A <a href="http://root.cern.ch/root/html/tutorials/pythia/pythia8.C.html"> 
basic example</a> for generating minimum-bias events with PYTHIA 8 inside 
a ROOT macro, and filling some histograms with the kinematics of the 
final-state particles is provided in
<pre>
    yourROOTinstallationDirectory/tutorials/pythia/pythia8.C
</pre>
Note that before executing this script
<ul>
<li>the environment variable <code>PYTHIA8</code> must point to the 
<code>pythia8100</code> (or newer) installation directory, and</li>
<li>the environment variable <code>PYTHIA8DATA</code> must be defined 
and it must point to <code>$PYTHIA8/xmldoc</code>.</li>
</ul>

<p/>
Looking at the example code you will see that it is necessary to 
load three libraries before running the actual code:
<pre>
    gSystem->Load("$PYTHIA8/lib/libpythia8"); // Pythia 8
    gSystem->Load("libEG"); // The library with the TGenerator interface
    gSystem->Load("libEGPythia8"); // The TPythia8 implementation
</pre>

<h3>A second example</h3>

It is not necessary to run PYTHIA as a ROOT plug-in. One can also perform 
the generation and analysis of events in a completely standalone fashion,
and only use ROOT for the histogramming step. One example, with a   
lightly modified version of <code>main01.cc</code>, is
<pre>
//gSystem.Load("../libPythia8");
// File: main01.cc
// This is a simple test program. It fits on one slide in a talk. 
// It studies the charged multiplicity distribution at the LHC.
// Copyright C 2007 Torbjorn Sjostrand
//#include "Pythia.h"
#include "TH1.h"
using namespace Pythia8; 
int ex1() {
  // Generator. Process selection. LHC initialization. Histogram.
  Pythia pythia;
  pythia.readString("HardQCD:all = on");    
  pythia.readString("PhaseSpace:pTHatMin = 20.");  
  pythia.init( 2212, 2212, 14000.);
  TFile *file = TFile::Open("ex1.root","recreate");
  Event *event = &pythia.event;
  TTree *T = new TTree("T","ev1 Tree");
  T->Branch("event","Event",&event);
  TH1F *mult = new TH1F("mult","charged multiplicity", 100, -0.5, 799.5);
  // Begin event loop. Generate event. Skip if error. List first one.
  for (int iEvent = 0; iEvent < 100; ++iEvent) {
    if (!pythia.next()) continue;
    //if (iEvent < 1) {pythia.info.list(); pythia.event.list();} 
    // Find number of all final charged particles and fill histogram.
    int nCharged = 0;
    for (int i = 0; i < pythia.event.size(); ++i) 
      if (pythia.event[i].isFinal() && pythia.event[i].isCharged()) 
        ++nCharged; 
    mult->Fill( nCharged );
    T->Fill();
  // End of event loop. Statistics. Histogram. Done.
  }
  pythia.statistics();
  cout << mult; 
  T->Print();
  T->Write();
  delete file;
  return 0;
}
</pre>

</body>
</html>

<!-- Copyright (C) 2010 Torbjorn Sjostrand -->
