#include <graphics.h>
#include <stdio.h>
#include <dos.h>  


void drawLine(int x1, int y1, int x2, int y2) {
    int dx = abs(x2 - x1), dy = abs(y2 - y1);
    int sx = (x1 < x2) ? 1 : -1;
    int sy = (y1 < y2) ? 1 : -1;
    int err = dx - dy, e2;

    while (x1 != x2 || y1 != y2) {
        putpixel(x1, y1, WHITE);
        e2 = 2 * err;
        if (e2 > -dy) { err -= dy; x1 += sx; }
        if (e2 < dx) { err += dx; y1 += sy; }
        delay(2);
    }
}

void drawA(int x, int y) { drawLine(x, y + 30, x + 10, y); drawLine(x + 10, y, x + 20, y + 30); drawLine(x + 5, y + 15, x + 15, y + 15); }
void drawC(int x, int y) { drawLine(x + 20, y, x, y); drawLine(x, y, x, y + 30); drawLine(x, y + 30, x + 20, y + 30); }
void drawH(int x, int y) { drawLine(x, y, x, y + 30); drawLine(x + 20, y, x + 20, y + 30); drawLine(x, y + 15, x + 20, y + 15); }
void drawY(int x, int y) { drawLine(x, y, x + 10, y + 15); drawLine(x + 20, y, x + 10, y + 15); drawLine(x + 10, y + 15, x + 10, y + 30); }
void drawU(int x, int y) { drawLine(x, y, x, y + 30); drawLine(x + 20, y, x + 20, y + 30); drawLine(x, y + 30, x + 20, y + 30); }
void drawT(int x, int y) { drawLine(x, y, x + 20, y); drawLine(x + 10, y, x + 10, y + 30); }
void drawH2(int x, int y) { drawH(x, y); }


void draw1(int x, int y) {
    drawLine(x + 10, y, x + 10, y + 30);
}


void draw5(int x, int y) {
    drawLine(x + 20, y, x, y);
    drawLine(x, y, x, y + 15);
    drawLine(x, y + 15, x + 20, y + 15);
    drawLine(x + 20, y + 15, x + 20, y + 30);
    drawLine(x, y + 30, x + 20, y + 30);
}

int main() {
    int gd = DETECT, gm;
    initgraph(&gd, &gm, "C:\\Turboc3\\BGI");
    drawA(50, 100); drawC(80, 100); drawH(110, 100); drawY(140, 100);
    drawU(180, 100); drawT(210, 100); drawH2(240, 100);
    draw1(290, 100); draw5(320, 100);

    getch();
    closegraph();
    return 0;
}