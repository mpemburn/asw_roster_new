<div class="col-md-12 {{ (empty($static->coven)) ? 'hide' : 'show' }}">
    <div class="col-md-2">
        <strong>Coven: </strong>
    </div>
    <div class="col-md-10">
        {{ $static->coven }}
    </div>
</div>
<div class="col-md-12 {{ (empty($static->leadership)) ? 'hide' : 'show' }}">
    <div class="col-md-2">
        <strong>Role: </strong>
    </div>
    <div class="col-md-10">
        {{ $static->leadership }}
    </div>
</div>
<div class="col-md-12 {{ (empty($static->degree)) ? 'hide' : 'show' }}">
    <div class="col-md-2">
        <strong>Degree: </strong>
    </div>
    <div class="col-md-10">
        {{ $static->degree }}
    </div>
</div>
<div class="col-md-12 {{ (empty($static->bonded)) ? 'hide' : 'show' }}">
    <div class="col-md-2">
        <strong>Bonded: </strong>
    </div>
    <div class="col-md-10">
        {{ $static->bonded }}
    </div>
</div>
<div class="col-md-12 {{ (empty($static->solitary)) ? 'hide' : 'show' }}">
    <div class="col-md-2">
        <strong>Solitary: </strong>
    </div>
    <div class="col-md-10">
        {{ $static->solitary }}
    </div>
</div>
<div class="col-md-12 {{ (empty($static->board)) ? 'hide' : 'show' }}">
    <div class="col-md-2">
        <strong>Board: </strong>
    </div>
    <div class="col-md-10">
        {{ $static->board }} until {{ $static->board_expiry }}
    </div>
</div>
