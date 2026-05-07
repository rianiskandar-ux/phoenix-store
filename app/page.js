import HeroSection from "@/components/home/HeroSection";
import AboutSection from "@/components/home/AboutSection";
import WorkflowSection from "@/components/home/WorkflowSection";
import VideoSection from "@/components/home/VideoSection";
import CtaSection from "@/components/home/CtaSection";
import FooterSection from "@/components/home/FooterSection";

export default function Home() {
    return (
        <div style={{ marginTop: "-60px" }}>
            <HeroSection />
            <AboutSection />
            <WorkflowSection />
            <VideoSection />
            <CtaSection />
            <FooterSection />
        </div>
    );
}
