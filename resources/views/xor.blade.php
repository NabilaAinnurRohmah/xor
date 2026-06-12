<!DOCTYPE html>
<html>

<head>

    <title>XOR Cipher</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 30px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            margin: auto;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .hasil {
            background: #eee;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            word-break: break-all;
        }
    </style>

</head>

<body>


    <div class="container">
        @if (session('error'))
            <p style="color:red">
                {{ session('error') }}
            </p>
        @endif
        <h2>XOR Cipher</h2>

        <form method="POST" action="/xor">

            @csrf

            <label>Mode</label>

            <select name="mode">

                <option value="encrypt" {{ isset($mode) && $mode == 'encrypt' ? 'selected' : '' }}>
                    Enkripsi
                </option>

                <option value="decrypt" {{ isset($mode) && $mode == 'decrypt' ? 'selected' : '' }}>
                    Dekripsi
                </option>

            </select>

            <label>Input Text / Binary</label>

            <input type="text" name="text" placeholder="HELLO atau 01000001 01000010" value="{{ $input ?? '' }}">

            <label>Key</label>

            <input type="text" name="key" value="{{ $key ?? '' }}">

            <button type="submit">
                Process
            </button>

        </form>

        @isset($resultBinary)

            @if ($mode == 'encrypt')
                <h3>Hasil Enkripsi Binary</h3>

                <div class="hasil">
                    {{ $resultBinary }}
                </div>

                <h3>Hasil Enkripsi Text</h3>

                <div class="hasil">
                    {{ $resultText }}
                </div>
            @else
                <h3>Hasil Dekripsi Binary</h3>

                <div class="hasil">
                    {{ $resultBinary }}
                </div>

                <h3>Hasil Dekripsi Text</h3>

                <div class="hasil">
                    {{ $resultText }}
                </div>
            @endif

        @endisset

    </div>

</body>

</html>
