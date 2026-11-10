<!-- Mahasiswa (abstract)
                  - nim | - nama | - prodi | - semester
          + getNim() | + getNama() | + getProdi() | + getSemester()
                       + hitungBiaya(): int (abstract)
                       + getJenis(): string (abstract)
                                   |
                                 (is-a)
                                   |
               +-------------------+-------------------+
               |                                       |
       MahasiswaReguler extends                MahasiswaBeasiswa extends 
       Mahasiswa                               Mahasiswa
       
- ukt: int                              - ukt: int
                                        - potonganPersen: int (0-100)

+ __construct(...)                      + __construct(...)
+ getUkt(): int                         + getUkt(): int
+ hitungBiaya(): int                    + getPotonganPersen(): int
+ getJenis(): string                    + hitungBiaya(): int
                                        + getJenis(): string --

// Diagram ini menjelaskan untuk menggambar kan hubungan pewarisan antar extends pada mahasiswa 