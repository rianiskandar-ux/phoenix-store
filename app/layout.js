import { Inter } from "next/font/google";
import "./globals.css";
import Navbar from "@/components/layout/Navbar";

const inter = Inter({ subsets: ["latin"] });

export const metadata = {
  title: "Phoenix Whistleblowing Software",
  description: "Inspiring Integrity, Guiding Growth",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body className={inter.className}>
        <Navbar />
        <main style={{paddingTop: "60px"}}>
          {children}
        </main>
      </body>
    </html>
  );
}