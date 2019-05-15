<html>
<head>
<title>Parton Distributions</title>
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

<form method='post' action='PartonDistributions.php'>

<h2>Parton Distributions</h2>

The parton distributions file contains the <code>PDF</code> class. 
<code>PDF</code> is the base class, from which specific <code>PDF</code> 
classes are derived.

<p/>
The choice of which PDF to use is made by settings in the 
<code>Pythia</code> class, see <?php $filepath = $_GET["filepath"];
echo "<a href='PDFSelection.php?filepath=".$filepath."' target='page'>";?>here</a>. 
These settings also allow to access all the proton PDF's available in the
LHAPDF library [<a href="Bibliography.php" target="page">Wha05</a>]. Thus there is no need for a normal user 
to study the <code>PDF</code> class. The structure must only be understood 
when interfacing new PDF's, e.g. ones not yet found in LHAPDF. 

<h3>The PDF base class</h3>

<code>PDF</code> defines the interface that all PDF classes should respect.
The constructor requires the incoming beam species to be given:
even if used for a proton PDF, one needs to know whether the beam
is actually an antiproton. This is one of the reasons why <code>Pythia</code> 
always defines two PDF objects in an event, one for each beam.

<p/>
Once a <code>PDF</code> object has been constructed, call it <code>pdf</code>, 
the main method is <code>pdf.xf( id, x, Q2)</code>, which returns 
<i>x*f_id(x, Q2)</i>, properly taking into account whether the beam 
is an antiparticle or not.

<p/>
Whenever the <code>xf</code> member is called with a new flavour, <i>x</i> 
or <i>Q^2</i>, the <code>xfUpdate</code> member is called to do the actual 
updating. This routine may either update that particular flavour or all 
flavours at this <i>(x, Q^2)</i> point. (In the latter case the saved 
<code>id</code> value <code>idSav</code> should be set to 9.) The choice is 
to be made by the producer of a given set, based on what he/she deems most 
effective, given that sometimes only one flavour need be evaluated, and 
about equally often all flavours are needed at the same <i>x</i> and 
<i>Q^2</i>. Anyway, the latest value is always kept in memory. This is 
the other reason why <code>Pythia</code> has one separate <code>PDF</code> 
object for each beam, so that values at different <i>x</i> can be kept 
in memory. 

<p/>
Two further public methods are <code>xfVal( id, x, Q2)</code> and 
<code>xfSea( id, x, Q2)</code>. These are simple variants whereby
the quark distributions can be subdivided into a valence and a sea part.
If these are not directly accessible in the parametrization, onc can
make the simplified choices <i>u_sea = ubar_sea, u_val = u_tot - u_sea</i>,
and correspondingly for <i>d</i>. (Positivity will always be guaranteed
at output.) The <code>xfUpdate</code> method should also take care of
updating this information.

<p/>
Since the current LHAPDF library does not check <i>x</i> and <i>Q2</i>
limits (except in its LHAGLUE interface) the routine 
<code>setLimits( xMin, xMax, Q2Min, Q2Max)</code> allows you 
to set lower and upper limits beyond which the distributions are frozen
or, for <i>x_max</i> set to vanish. This method has only and effect on
the LHAPDF class below. If you implement a new PDF you are free to use this 
method, but it would be smarter to hardcode the desired limiting behaviour 
As LHAPDF evolves this method may become superfluous.

<h3>Derived classes</h3>

There is only one pure virtual method, <code>xfUpdate</code>, that therefore 
must be implemented in any derived class. Currently the list of such 
classes is tiny:

<p/>
For protons:
<ul>
<li><code>GRV94L</code> gives the GRV 94 L parametrization 
[<a href="Bibliography.php" target="page">Glu95</a>].</li>
<li><code>CTEQ5L</code> gives the CTEQ 5 L parametrization 
[<a href="Bibliography.php" target="page">Lai00</a>].</li>
<li><code>LHAPDFinterface</code> provides an interface to the 
LHAPDF library[<a href="Bibliography.php" target="page">Wha05</a>].</li>
</ul>
The default is CTEQ 5L, which is the most recent of the two hardcoded sets.

<p/>
For charged leptons (e, mu, tau): 
<ul>
<li>Lepton gives a QED parametrization [<a href="Bibliography.php" target="page">Kle89</a>].
In QED there are not so many ambiguities, so here one set should be 
enough. On the other hand, there is the problem that the 
lepton-inside-lepton pdf is integrably divergent for <i>x -> 1</i>, 
which gives numerical problems. Like in PYTHIA 6, the pdf is therefore
made to vanish for <i>x > 1 - 10^{-10}</i>, and scaled up in the range
<i>1 - 10^{-7} &lt; x &lt; 1 - 10^{-10}</i> in such a way that the 
total area under the pdf is preserved.</li>
</ul>   

There is another method, <code>isSetup()</code>, that returns the 
base-class boolean variable <code>isSet</code>. This variable is 
initially <code>true</code>, but could be set <code>false</code> if the 
setup procedure of a PDF failed, e.g. if the user has chosen an unknown 
PDF set.  

</body>
</html>

<!-- Copyright C 2007 Torbjorn Sjostrand -->