<?php if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */ ?>
<div class="pressrelease">
	<h2>Frequently Asked Questions</h2>
	<p>This section will discuss some questions that come up fairly often. If you have any other relevant questions, feel free to join the <a href="/static/irc/">IRC channel</a>.</p>

	<h3>How can I join Anonymous?</h3>
	<p>This question comes up quite often. Anonymous does not have a membership list, and you can't really 'join' it either. If you identify with or say you are Anonymous, you <em>are</em> Anonymous.
	Noone has the authority to say whether you are Anonymous or not, except for yourself.</p>
	
	<h3>How can I talk to Anonymous?</h3>
	<p>Anons can be found all over the world - and all over the internet. There are no leaders or official spokespersons, and no official websites, IRC networks, or anything else. Basically, if you want
	to talk to Anonymous, join a random IRC network or forum and start talking to anons! A starting point may be, for example, the <a href="/static/irc/">AnonNews IRC channel</a>.</p>
	
	<h3>Isn't AnonNews just for AnonOps?</h3>
	<p>No, not at all! Any anon is welcome to post on AnonNews, and there is no active affiliation with AnonOps. You can read more about this issue <a href="/static/anon/">here</a>.</p>
	
	<h3>I don't like this press release! How can I get it removed?</h3>
	<p>You can't. AnonNews is <a href="/static/moderation/">uncensored (but moderated)</a>, and everyone has equal rights to post press releases (or forum posts, or anything else). As long as something
	is relevant and fits the guidelines, it will be published, regardless of pressure to take it down. There is one exception to this rule, and that is press releases that pretend to be made by the staff
	of a specific network or operation, while actually being made by an outsider. In this case the network/operation staff can request removal of the press release (this only goes for operations and
	networks with a defined leadership structure). Other than the aforementioned situation, don't bother trying to get something removed.</p>
	
	<h3>Why was my submission rejected?</h3>
	<p>If your submission doesn't show up after a while, that means it probably didn't fit the guidelines. If you think a submission was rejected in error, you can contact an administrator in the
	<a href="/static/irc/">IRC channel</a>. Please don't resubmit your submissions.</p>
	
	<h3>How do I upvote a press release?</h3>
	<p>To upvote a press release, you will have to post a comment that is at least 2 lines long, and at least 100 characters long. This is to prevent pointless '+1' posts just to upvote a press release.
	Simply enter a comment, and if your comment is long enough, a checkbox to upvote the press release will appear on the captcha verification page.</p>
	
	<h3>Do you keep IPs?</h3>
	<p>Short answer: no.</p>
	<p>Longer answer: no, with a few exceptions. To prevent double voting, salted hashes of partial IPs are kept - these should be practically useless if someone were to gain access to the database.
	The fact that only partial IPs are used for these hashes means that occasionally a vote may not be counted correctly, however this should not happen very often. The other situation is the spamfilter.
	If your submission hits a severe spamfilter, your submission will be blocked, and the IP you are submitting from will be saved - the submission will be reviewed in 24 hours, and if it turns out your
	submission was legitimate, your IP will be removed from the system. Most 'suspicious' submissions will be held for review, rather than being outright blocked - in this case, your IP is NOT kept. If
	you do manage to hit a severe spamfilter and your submission was indeed malicious/spam, your IP may be banned (thus stored in the banlist).</p>
	<p>No access logs or other logs with identifying information are kept on the server. You can visit the site and post submissions from TOR, VPNs, or other anonymization networks, however most of the
	time your submission will be held for review when using one of these methods.</p>
	
	<h3>How does the spamfilter work?</h3>
	<p>AnonNews uses a custom spam score system, where a 'score' is assigned, depending on several characteristics of a submission. Several factors that play a role for submissions are banned IPs, blocked
	domains, blacklisted keywords, DNSBL-listed IPs, and other things. Depending on your spam score, the submission is either directly visible, held for manual review, or outright blocked. For comments,
	several text characteristics are analyzed such as the amount of lines, average length and variation in length of lines, special characters ratio, and amount of URLs.</p>
	
	<h3>Can I use the press releases or forum posts on AnonNews elsewhere?</h3>
	<p>Yes, all user-submitted content is automatically licensed under a Creative Commons Attribution license. This means that you are free to reuse and remix content, both for commercial and non-commercial
	purposes, as long as you give credit to the original author. If no specific author is outlined, you should attribute to 'Anonymous' and place a backlink to the relevant page on AnonNews.</p>
	
	<h3>Can I use the design / source code / etc. of AnonNews?</h3>
	<p>Yes, AnonNews is licensed under the WTFPL, meaning you can pretty much do with it what you want. No attribution required, no restrictions whatsoever. Be aware that some third-party code is used
	that may have separate restrictions - this is detailed in the LICENSE file of the source code package. You can download the source code <a href="http://www.cryto.net/projects/anonnews2/">here</a>.</p>
</div>
