import os

def copy_php_to_txt(source_folder, output_file):
    with open(output_file, 'w', encoding='utf-8') as out_file:
        for file_name in os.listdir(source_folder):
            if file_name.endswith('.php'):
                file_path = os.path.join(source_folder, file_name)
                with open(file_path, 'r', encoding='utf-8') as in_file:
                    content = in_file.read()
                
                out_file.write(f"{file_name}\n")
                out_file.write(f"{content}\n\n")  # Tambahkan dua baris baru sebagai pemisah

source_folder = r'C:\xampp\htdocs\bergambar\bergambar\app\Http\Controllers'  # Ganti dengan path folder yang berisi file PHP
output_file = r'C:\xampp\htdocs\bergambar\bergambar\database\migrations\controller.txt'
copy_php_to_txt(source_folder, output_file)
print(f"Isi file PHP telah disalin ke {output_file}")
