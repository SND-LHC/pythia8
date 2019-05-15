<html>
<head>
<title>Event Information</title>
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

<form method='post' action='EventInformation.php'>

<h2>Event Information</h2>

The <code>Info</code> class collects various one-of-a-kind information, 
some relevant for all events and others for the current event. 
An object <code>info</code> is a public member of the <code>Pythia</code>
class, so if you e.g. have declared <code>Pythia pythia</code>, the
<code>Info</code> methods can be accessed by 
<code>pythia.info.method()</code>. Most of this is information that 
could also be obtained e.g. from the event record, but is here more
directly available. It is intended for processes generated internally
in Pythia, not for ones read in e.g. via the Les Houches Accord.

<p/>
Here are the currently available methods related to each event:

<p/><code>method&nbsp; </code><strong> list() &nbsp;</strong> <br/>
a listing of most of the information set for the current event. 
  

<p/><code>method&nbsp; </code><strong> idA(), idB() &nbsp;</strong> <br/>
the identities of the two beam particles. 
  

<p/><code>method&nbsp; </code><strong> pzA(), pzB() &nbsp;</strong> <br/>
the longitudinal momenta of the two beam particles.
  

<p/><code>method&nbsp; </code><strong> eA(), eB() &nbsp;</strong> <br/>
the energies of the two beam particles.
  

<p/><code>method&nbsp; </code><strong> mA(), mB() &nbsp;</strong> <br/>
the masses of the two beam particles.
  

<p/><code>method&nbsp; </code><strong> eCM(), s() &nbsp;</strong> <br/>
the cm energy and its square for the two beams. 
  

<p/><code>method&nbsp; </code><strong> name(), code() &nbsp;</strong> <br/>
the name and code of the process that occured.
  

<p/><code>method&nbsp; </code><strong> nFinal() &nbsp;</strong> <br/>
the number of final-state partons in the hard process.
  

<p/><code>method&nbsp; </code><strong> isResolved() &nbsp;</strong> <br/>
are beam particles resolved, i.e. were PDF's used for the process?
  

<p/><code>method&nbsp; </code><strong> isDiffractiveA(), isDiffractiveB() &nbsp;</strong> <br/>
is either beam diffractively excited?
  

<p/><code>method&nbsp; </code><strong> isMinBias() &nbsp;</strong> <br/>
is the process a minimum-bias one?
  

<p/><code>method&nbsp; </code><strong> hasSub() &nbsp;</strong> <br/>
does the process have a subprocess classification?
Currently only true for minbias, where it allows the hardest
collision to be identified.
  

<p/><code>method&nbsp; </code><strong> nameSub(), codeSub(), nFinalSub() &nbsp;</strong> <br/>
the name, code and number of final-state partons in the subprocess
that occured when <code>hasSub()</code> is true. For instance, for 
a minimum-bias event the <code>code</code> would always be 101,
while <code>codeSub()</code> would vary depending on the actual
hardest interaction, e.g. 111 for <i>g g -> g g</i>. The methods
below would also provide information for this particular subcollision.  
  

<p/><code>method&nbsp; </code><strong> id1(), id2() &nbsp;</strong> <br/>
the identities of the two partons coming in to the hard process.
  

<p/><code>method&nbsp; </code><strong> x1(), x2() &nbsp;</strong> <br/>
<i>x</i> fractions of the two partons coming in to the hard process.
  

<p/><code>method&nbsp; </code><strong> y(), tau() &nbsp;</strong> <br/>
rapidity and scaled mass-squared of the hard-process subsystem, as 
defined by the above <i>x</i> values. 
  

<p/><code>method&nbsp; </code><strong> pdf1(), pdf2() &nbsp;</strong> <br/>
parton densities <i>x*f(x,Q^2</i> )evaluated for the two incoming 
partons; could be used e.g. for reweighting purposes. 
  

<p/><code>method&nbsp; </code><strong> QFac(), Q2Fac() &nbsp;</strong> <br/>
the <i>Q^2</i> or <i>Q^2</i> factorization scale at which the 
densities were evaluated.
  

<p/><code>method&nbsp; </code><strong> alphaS(), alphaEM() &nbsp;</strong> <br/>
the <i>alpha_strong</i> and <i>alpha_electromagnetic</i> values used 
for the hard process.
  

<p/><code>method&nbsp; </code><strong> QRen(), Q2Ren() &nbsp;</strong> <br/>
the <i>Q</i> or <i>Q^2</i> renormalization scale at which 
<i>alpha_strong</i> and <i>alpha_electromagnetic</i> were evaluated.
  

<p/><code>method&nbsp; </code><strong> mHat(), sHat() &nbsp;</strong> <br/>
the invariant mass and its square for the hard process.
  

<p/><code>method&nbsp; </code><strong> tHat(), uHat() &nbsp;</strong> <br/>
the remaining two Mandelstam variables; only defined for <i>2 -> 2</i>
processes. 
  

<p/><code>method&nbsp; </code><strong> pTHat(), pT2Hat() &nbsp;</strong> <br/>
transverse momentum and its square in the rest frame of a <i>2 -> 2</i>
processes. 
  

<p/><code>method&nbsp; </code><strong> m3Hat(), m4Hat() &nbsp;</strong> <br/>
the masses of the two outgoing particles in a <i>2 -> 2</i> processes. 
  

<p/><code>method&nbsp; </code><strong> thetaHat(), phiHat() &nbsp;</strong> <br/>
the polar and azimuthal scattering angles in the rest frame of 
a <i>2 -> 2</i> process.
  

<p/><code>method&nbsp; </code><strong> bMI() &nbsp;</strong> <br/>
the impact parameter <i>b</i> assumed for the current collision when
multiple interactions are simulated. Is not expressed in any physical
size (like fm), but only rescaled so that the average should be unity 
for minimum-bias events (meaning less than that for events with hard
processes). 
  

<p/><code>method&nbsp; </code><strong> enhanceMI() &nbsp;</strong> <br/>
The choice of impact parameter implies an enhancement or depletion of
the rate of subsequent interactions, as given by this number. Again
the average is normalized be unity for minimum-bias events (meaning 
more than that for events with hard processes).  
  

<p/><code>method&nbsp; </code><strong> nMI() &nbsp;</strong> <br/>
the number of hard interactions in the current event. Is 0 for elastic
and diffractive events, and else at least 1, with more possible from
multiple interactions.
  

<p/><code>method&nbsp; </code><strong> codeMI(i), pTMI(i) &nbsp;</strong> <br/>
the process code and transverse momentum of the <code>i</code>'th 
subprocess, with <code>i</code> in the range from 0 to
<code>nMI() - 1</code>. The values for subprocess 0 is redundant with
information already provided above.  
  

<p/><code>method&nbsp; </code><strong> nISR(), nFSRinProc(), nFSRinRes() &nbsp;</strong> <br/>
the number of emissions in the initial-state showering, in the final-state
showering excluding resonance decys, and in the final-state showering
inside resonance decays, respectively. Not yet fully implemented.  
  

<p/>
Here are the currently available methods related to the event sample 
as a whole. While continuously updated during the run, it is recommended
only to study these properties at the end of the event generation, 
when the full statistics is available.

<p/><code>method&nbsp; </code><strong> nTried(), nSelected(), nAccepted() &nbsp;</strong> <br/>
the total number of tried phase-space points, selected hard processes
and finally accepted events, summed over all allowed subprocesses.
The first number is only intended for a study of the phase-space selection
efficiency. The last two numbers only disagree if the user introduces some
veto during the event-generation process; then the former is the number of
acceptable events found by Pythia and the latter the number that also
were approved by the user.

<p/><code>method&nbsp; </code><strong> sigmaGen(), sigmaErr() &nbsp;</strong> <br/>
the estimated cross section and its estimated error,
summed over all allowed subprocesses, in units of mb. The numbers refer to
the accepted event sample above, i.e. after any user veto. 

</body>
</html>

<!-- Copyright C 2007 Torbjorn Sjostrand -->