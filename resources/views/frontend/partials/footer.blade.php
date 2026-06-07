@php
    use App\Models\Setting;

    $agencyName = Setting::getValue('agency_name', 'Ủy ban nhân dân xã Vĩnh Bình');
    $address = Setting::getValue('address');
    $phone = Setting::getValue('phone');
    $email = Setting::getValue('email');
    $footerText = Setting::getValue('footer_text');
@endphp

<footer class="footer">
    <p>
        <strong>Cơ quan chủ quản:</strong>
        {{ $agencyName }}
    </p>

    @if($address)
        <p>
            <strong>Địa chỉ:</strong>
            {{ $address }}
        </p>
    @endif

    @if($phone || $email)
        <p>
            @if($phone)
                <strong>Điện thoại:</strong>
                {{ $phone }}
            @endif

            @if($email)
                |
                <strong>Email:</strong>
                {{ $email }}
            @endif
        </p>
    @endif

    @if($footerText)
        <p>
            {!! nl2br(e($footerText)) !!}
        </p>
    @else
        <p>
            Ghi rõ nguồn khi phát hành lại thông tin từ website này.
        </p>
    @endif
</footer>