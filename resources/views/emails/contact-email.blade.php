<h2>Subject: {{$subject}}</h2>
<br>
<h2>From: {{ $full_name }} - {{$email}}</h2>
<br>
@if($is_portfolio_contact && $portfolio_user)
@if($is_hire_inquiry)
<h2>Hire Inquiry:</h2>
@else
<h2>Portfolio Contact:</h2>
@endif
<p><strong>Portfolio Owner:</strong> {{ $portfolio_user->name }}</p>
<p><strong>Portfolio URL:</strong> {{ url('/' . ($portfolio_user->portfolio_slug ?: $portfolio_user->username)) }}</p>
@if($portfolio_user->profession)
<p><strong>Profession:</strong> {{ $portfolio_user->profession }}</p>
@endif
@if($is_hire_inquiry)
<p><strong>Type:</strong> <span style="color: #ff6b35; font-weight: bold;">HIRING INQUIRY - This person is looking for work opportunities</span></p>
@endif
<br>
@endif
<h2>Message:</h2>
<br>
<p>{{$_message}}</p>
<br>
<h2>IP: {{ $ip }}</h2>

